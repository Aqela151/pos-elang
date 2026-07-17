import { useState } from "react";
import { X, AlertTriangle } from "lucide-react";
import api from "../../services/api";
import "./DeleteCabangModal.css";

function DeleteCabangModal({ isOpen, onClose, cabang, onConfirm }) {
  const [submitting, setSubmitting] = useState(false);
  const [serverError, setServerError] = useState("");

  if (!isOpen || !cabang) return null;

  const handleClose = () => {
    setServerError("");
    onClose();
  };

  const handleDelete = async () => {
    setSubmitting(true);
    setServerError("");
    try {
      await api.delete(`/cabang/${cabang.id}`);
      if (onConfirm) onConfirm(cabang);
      handleClose();
    } catch (err) {
      console.error(err);
      const msg =
        err?.response?.data?.message ||
        "Gagal menghapus data cabang. Silakan coba lagi.";
      setServerError(msg);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="delete-modal-overlay" onClick={handleClose}>
      <div className="delete-modal" onClick={(e) => e.stopPropagation()}>
        <div className="delete-modal-header">
          <button type="button" className="delete-modal-close" onClick={handleClose}>
            <X size={18} />
          </button>
        </div>

        <div className="delete-modal-body">
          <div className="delete-modal-icon">
            <AlertTriangle size={26} />
          </div>
          <h3>Hapus Cabang?</h3>
          <p>
            Data cabang <strong>{cabang.nama_cabang}</strong> akan dihapus secara permanen dan tidak
            dapat dikembalikan.
          </p>

          {serverError && <div className="delete-modal-error">{serverError}</div>}
        </div>

        <div className="delete-modal-footer">
          <button type="button" className="btn-secondary" onClick={handleClose} disabled={submitting}>
            Batal
          </button>
          <button type="button" className="btn-danger" onClick={handleDelete} disabled={submitting}>
            {submitting ? "Menghapus..." : "Ya, Hapus"}
          </button>
        </div>
      </div>
    </div>
  );
}

export default DeleteCabangModal;