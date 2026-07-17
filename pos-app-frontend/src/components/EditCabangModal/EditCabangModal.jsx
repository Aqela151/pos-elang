import { useState, useEffect } from "react";
import { X, Building2 } from "lucide-react";
import api from "../../services/api";
import "./EditCabangModal.css";

function EditCabangModal({ isOpen, onClose, cabang, onSave, onSuccess }) {
  const emptyForm = {
    nama_cabang: "",
    alamat: "",
    telepon: "",
  };

  const [form, setForm] = useState(emptyForm);
  const [errors, setErrors] = useState({});
  const [submitting, setSubmitting] = useState(false);
  const [serverError, setServerError] = useState("");

  useEffect(() => {
    if (cabang) {
      setForm({
        nama_cabang: cabang.nama_cabang || "",
        alamat: cabang.alamat || "",
        telepon: cabang.telepon || "",
      });
      setErrors({});
      setServerError("");
    }
  }, [cabang]);

  if (!isOpen || !cabang) return null;

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
    setErrors((prev) => ({ ...prev, [name]: "" }));
  };

  const validate = () => {
    const newErrors = {};
    if (!form.nama_cabang.trim()) newErrors.nama_cabang = "Nama cabang wajib diisi";
    if (!form.alamat.trim()) newErrors.alamat = "Alamat wajib diisi";
    if (!form.telepon.trim()) newErrors.telepon = "Telepon wajib diisi";
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleClose = () => {
    setErrors({});
    setServerError("");
    onClose();
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setServerError("");

    if (!validate()) return;

    setSubmitting(true);
    try {
      const res = await api.put(`/cabang/${cabang.id}`, form);
      const updated = res?.data?.data || res?.data || { ...cabang, ...form };

      if (onSave) onSave(updated);
      if (onSuccess) onSuccess();
      handleClose();
    } catch (err) {
      console.error(err);
      const msg =
        err?.response?.data?.message ||
        "Gagal memperbarui data cabang. Silakan coba lagi.";
      setServerError(msg);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="cabang-modal-overlay" onClick={handleClose}>
      <div className="cabang-modal" onClick={(e) => e.stopPropagation()}>
        <div className="cabang-modal-header">
          <div className="cabang-modal-title">
            <div className="cabang-modal-icon">
              <Building2 size={18} />
            </div>
            <div>
              <h3>Edit Cabang</h3>
              <p>Perbarui data cabang</p>
            </div>
          </div>
          <button type="button" className="cabang-modal-close" onClick={handleClose}>
            <X size={18} />
          </button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className="cabang-modal-body">
            {serverError && <div className="cabang-modal-error">{serverError}</div>}

            <div className="form-group">
              <label>Nama Cabang</label>
              <input
                type="text"
                name="nama_cabang"
                placeholder="Contoh: Cabang Malang"
                value={form.nama_cabang}
                onChange={handleChange}
              />
              {errors.nama_cabang && <span className="field-error">{errors.nama_cabang}</span>}
            </div>

            <div className="form-group">
              <label>Alamat</label>
              <textarea
                name="alamat"
                placeholder="Contoh: Jl. Soekarno Hatta No. 10"
                value={form.alamat}
                onChange={handleChange}
                rows={3}
              />
              {errors.alamat && <span className="field-error">{errors.alamat}</span>}
            </div>

            <div className="form-group">
              <label>Telepon</label>
              <input
                type="text"
                name="telepon"
                placeholder="Contoh: 08123456789"
                value={form.telepon}
                onChange={handleChange}
              />
              {errors.telepon && <span className="field-error">{errors.telepon}</span>}
            </div>
          </div>

          <div className="cabang-modal-footer">
            <button type="button" className="btn-secondary" onClick={handleClose} disabled={submitting}>
              Batal
            </button>
            <button type="submit" className="btn-primary" disabled={submitting}>
              {submitting ? "Menyimpan..." : "Simpan Perubahan"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default EditCabangModal;