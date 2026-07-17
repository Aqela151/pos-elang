import { useState } from "react";
import { X, Building2 } from "lucide-react";
import api from "../../services/api";
import "./TambahCabangModal.css";

function TambahCabangModal({ isOpen, onClose, onSuccess }) {
  const initialForm = {
    nama: "",
    alamat: "",
    telepon: "",
  };

  const [form, setForm] = useState(initialForm);
  const [errors, setErrors] = useState({});
  const [submitting, setSubmitting] = useState(false);
  const [serverError, setServerError] = useState("");

  if (!isOpen) return null;

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
    setErrors((prev) => ({ ...prev, [name]: "" }));
  };

  const validate = () => {
    const newErrors = {};

    if (!form.nama.trim()) newErrors.nama = "Nama cabang wajib diisi";
    if (!form.alamat.trim()) newErrors.alamat = "Alamat wajib diisi";
    if (!form.telepon.trim()) newErrors.telepon = "Telepon wajib diisi";

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const resetAndClose = () => {
    setForm(initialForm);
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
      await api.post("/cabang", form);

      alert("Cabang berhasil ditambahkan");

      onSuccess?.();
      resetAndClose();
    } catch (err) {
      console.error(err);

      const msg =
        err?.response?.data?.message ||
        "Gagal menyimpan data cabang. Silakan coba lagi.";

      setServerError(msg);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="cabang-modal-overlay" onClick={resetAndClose}>
      <div
        className="cabang-modal"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="cabang-modal-header">
          <div className="cabang-modal-title">
            <div className="cabang-modal-icon">
              <Building2 size={18} />
            </div>

            <div>
              <h3>Tambah Cabang</h3>
              <p>Lengkapi data cabang baru</p>
            </div>
          </div>

          <button
            type="button"
            className="cabang-modal-close"
            onClick={resetAndClose}
          >
            <X size={18} />
          </button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className="cabang-modal-body">
            {serverError && (
              <div className="cabang-modal-error">
                {serverError}
              </div>
            )}

            <div className="form-group">
              <label>Nama Cabang</label>

              <input
                type="text"
                name="nama"
                placeholder="Contoh: Cabang Malang"
                value={form.nama}
                onChange={handleChange}
              />

              {errors.nama && (
                <span className="field-error">
                  {errors.nama}
                </span>
              )}
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

              {errors.alamat && (
                <span className="field-error">
                  {errors.alamat}
                </span>
              )}
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

              {errors.telepon && (
                <span className="field-error">
                  {errors.telepon}
                </span>
              )}
            </div>
          </div>

          <div className="cabang-modal-footer">
            <button
              type="button"
              className="btn-secondary"
              onClick={resetAndClose}
              disabled={submitting}
            >
              Batal
            </button>

            <button
              type="submit"
              className="btn-primary"
              disabled={submitting}
            >
              {submitting ? "Menyimpan..." : "Simpan Cabang"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default TambahCabangModal;