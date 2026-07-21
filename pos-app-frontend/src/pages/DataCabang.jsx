import { useState, useEffect } from "react";
import { Pencil, Trash2, Search, Plus, MapPin } from "lucide-react";
import { useAuth } from "../context/AuthContext";
import Card from "../components/Card/Card";
import TambahCabangModal from "../components/TambahCabangModal/TambahCabangModal";
import EditCabangModal from "../components/EditCabangModal/EditCabangModal";
import DeleteCabangModal from "../components/DeleteCabangModal/DeleteCabangModal";
import "./DataCabang.css";
import api from "../services/api";

function DataCabang() {
  const { user } = useAuth();
  const isAdmin = user?.role === "admin";
  const [cabangList, setCabangList] = useState([]);
  const [loading, setLoading] = useState(false);

  const [tambahOpen, setTambahOpen] = useState(false);
  const [editOpen, setEditOpen] = useState(false);
  const [deleteOpen, setDeleteOpen] = useState(false);
  const [selectedCabang, setSelectedCabang] = useState(null);

  const [searchTerm, setSearchTerm] = useState("");
  const [sortOrder, setSortOrder] = useState("newest");

  useEffect(() => {
    getCabang();
  }, []);

  const getCabang = async () => {
    setLoading(true);
    try {
      const res = await api.get("/cabang");

      const data = res.data;
      const normalized = Array.isArray(data)
        ? data
        : Array.isArray(data?.data)
        ? data.data
        : [];

      setCabangList(normalized);
    } catch (err) {
      console.error(err);
      setCabangList([]);
    } finally {
      setLoading(false);
    }
  };

  const handleAdd = () => {
    if (!isAdmin) return;
    setTambahOpen(true);
  };

  const handleEdit = (cabang) => {
    if (!isAdmin) return;
    setSelectedCabang(cabang);
    setEditOpen(true);
  };

  const handleDelete = (cabang) => {
    if (!isAdmin) return;
    setSelectedCabang(cabang);
    setDeleteOpen(true);
  };

  const handleSaveEdit = (updated) => {
    setCabangList((prev) =>
      prev.map((c) => (Number(c.id) === Number(updated.id) ? updated : c))
    );
  };

  const handleConfirmDelete = (target) => {
    setCabangList((prev) => prev.filter((c) => Number(c.id) !== Number(target.id)));
  };

  const safeCabang = Array.isArray(cabangList) ? cabangList : [];

  const filteredCabang = safeCabang.filter((cabang) => {
    const query = searchTerm.toLowerCase();
    const searchableText = `${cabang.nama_cabang || ""} ${cabang.alamat || ""} ${cabang.telepon || ""}`.toLowerCase();
    return searchableText.includes(query);
  });

  const sortedCabang = [...filteredCabang].sort((a, b) => {
    const idA = Number(a.id || 0);
    const idB = Number(b.id || 0);

    return sortOrder === "newest" ? idB - idA : idA - idB;
  });

  const totalCabang = safeCabang.length;
  const cabangDenganTelepon = safeCabang.filter((c) => c.telepon && String(c.telepon).trim() !== "").length;
  const cabangDenganAlamat = safeCabang.filter((c) => c.alamat && String(c.alamat).trim() !== "").length;
  const cabangTanpaTelepon = totalCabang - cabangDenganTelepon;

  return (
    <div className="cabang-container">
      <div className="stats-cards">
        <Card title="Total Cabang" value={totalCabang} description="Cabang terdaftar" />
        <Card title="Punya Kontak" value={cabangDenganTelepon} description="Nomor telepon terisi" />
        <Card title="Alamat Lengkap" value={cabangDenganAlamat} description="Alamat sudah diisi" />
        <Card title="Belum Ada Kontak" value={cabangTanpaTelepon} description="Perlu dilengkapi" />
      </div>

      <div className="filter-box">
        <div className="search-wrap">
          <Search size={16} className="search-icon" />
          <input
            type="text"
            placeholder="Cari nama cabang, alamat, atau telepon"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        <select className="filter-select" value={sortOrder} onChange={(e) => setSortOrder(e.target.value)}>
          <option value="newest">Terbaru</option>
          <option value="oldest">Terlama</option>
        </select>
        {isAdmin && (
          <button className="add-btn" onClick={handleAdd}>
            <Plus size={16} />
            Tambah Cabang
          </button>
        )}
      </div>

      <div className="cabang-table-card">
        <h3>Daftar Cabang</h3>
        <table>
          <thead>
            <tr>
              <th>NAMA CABANG</th>
              <th>ALAMAT</th>
              <th>TELEPON</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody>
            {sortedCabang.length === 0 ? (
              <tr>
                <td colSpan={4} className="empty-row">
                  {loading ? "Memuat data cabang..." : "Belum ada data cabang."}
                </td>
              </tr>
            ) : (
              sortedCabang.map((c) => (
                <tr key={c.id}>
                  <td>
                    <div className="cabang-info">
                      <div className="cabang-icon">
                        <MapPin size={16} />
                      </div>
                      <div>
                        <div className="cabang-name">{c.nama_cabang}</div>
                        <div className="cabang-sub">ID #{c.id}</div>
                      </div>
                    </div>
                  </td>
                  <td>{c.alamat || "-"}</td>
                  <td>{c.telepon || "-"}</td>
                  <td>
                    {isAdmin ? (
                      <div className="aksi-btns">
                        <Pencil size={16} onClick={() => handleEdit(c)} />
                        <Trash2 size={16} onClick={() => handleDelete(c)} />
                      </div>
                    ) : (
                      <span>-</span>
                    )}
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>

        <div className="pagination">
          <span className="pag-info">
            Menampilkan {sortedCabang.length} dari {safeCabang.length} cabang
          </span>
          <div className="pag-pages">
            <button className="pag-btn arrow">‹</button>
            <button className="pag-btn active">1</button>
            <button className="pag-btn">2</button>
            <button className="pag-btn">3</button>
            <span className="pag-dots">...</span>
            <button className="pag-btn arrow">›</button>
          </div>
        </div>
      </div>

      <TambahCabangModal
        isOpen={tambahOpen}
        onClose={() => setTambahOpen(false)}
        onSuccess={getCabang}
      />
      <EditCabangModal
        isOpen={editOpen}
        onClose={() => setEditOpen(false)}
        cabang={selectedCabang}
        onSave={handleSaveEdit}
        onSuccess={getCabang}
      />
      <DeleteCabangModal
        isOpen={deleteOpen}
        onClose={() => setDeleteOpen(false)}
        cabang={selectedCabang}
        onConfirm={handleConfirmDelete}
      />
    </div>
  );
}

export default DataCabang;