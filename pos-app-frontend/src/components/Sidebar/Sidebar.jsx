import { useState, useRef, useEffect } from "react";
import { NavLink } from "react-router-dom";
import "./Sidebar.css";
import { PanelLeft, MoreVertical } from "lucide-react";
import { useAuth } from "../../context/AuthContext";
import SidebarItem from "../SidebarItem/SidebarItem";
import dashboardIcon from "../../assets/icons/dashboard.png";
import keranjangIcon from "../../assets/icons/keranjang.png";
import barangIcon    from "../../assets/icons/barang.png";
import memberIcon    from "../../assets/icons/member.png";
import laporanIcon   from "../../assets/icons/laporan.png";
import settingsIcon  from "../../assets/icons/settings.png";
import logoutIcon    from "../../assets/icons/logout.png";

function Sidebar({ isOpen, onClose, collapsed, onToggleCollapse }) {
  const { user } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);
  const menuRef = useRef(null);

  const displayName = user?.nama || user?.name || user?.username || user?.email || "Pengguna";
  const displayRole = user?.role === "kasir" ? "Kasir" : user?.role === "admin" ? "Admin" : "Pengguna";
  const avatarInitial = displayName?.trim()?.[0]?.toUpperCase() || "P";

  const menuItems = user?.role === "kasir"
    ? [
        { text: "Kasir", icon: keranjangIcon, to: "/kasir" },
      ]
    : [
        { text: "Dashboard", icon: dashboardIcon, to: "/dashboard" },
        { text: "Kasir", icon: keranjangIcon, to: "/kasir" },
        { text: "Stok Barang", icon: barangIcon, to: "/stok-barang" },
        { text: "Data Member", icon: memberIcon, to: "/data-member" },
        { text: "Laporan", icon: laporanIcon, to: "/laporan" },
      ];

  // tutup dropdown kalau klik di luar area profile
  useEffect(() => {
    if (!menuOpen) return;
    const handleClickOutside = (e) => {
      if (menuRef.current && !menuRef.current.contains(e.target)) {
        setMenuOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, [menuOpen]);

  const handleMenuItemClick = () => {
    setMenuOpen(false);
    onClose?.();
  };

  return (
    <aside className={`sidebar ${isOpen ? "open" : ""} ${collapsed ? "collapsed" : ""}`}>

      <div className="sidebar-logo-bar">
        {!collapsed && <span className="sidebar-logo-name">POS ElangAnugerah</span>}
        <button className="sidebar-logo-btn" aria-label="Toggle" onClick={onToggleCollapse}>
          <PanelLeft size={14} strokeWidth={2} />
        </button>
      </div>

      {!collapsed && <p className="sidebar-section-label">Menu</p>}
      <ul className="sidebar-nav">
        {menuItems.map((item) => (
          <SidebarItem
            key={item.text}
            text={item.text}
            icon={item.icon}
            to={item.to}
            onClick={onClose}
            collapsed={collapsed}
          />
        ))}
      </ul>

      <div className="sidebar-spacer" />

      <div className="sidebar-divider" />

      <div className="sidebar-profile-wrap" ref={menuRef}>
        {menuOpen && (
          <div className="sidebar-profile-menu">
            <NavLink
              to="/settings"
              className="sidebar-profile-menu-item"
              onClick={handleMenuItemClick}
            >
              <img src={settingsIcon} alt="" className="sidebar-profile-menu-icon" draggable={false} />
              <span>Settings</span>
            </NavLink>
            <NavLink
              to="/logout"
              className="sidebar-profile-menu-item sidebar-profile-menu-item--logout"
              onClick={handleMenuItemClick}
            >
              <img src={logoutIcon} alt="" className="sidebar-profile-menu-icon" draggable={false} />
              <span>Log out</span>
            </NavLink>
          </div>
        )}

        <div className="sidebar-profile">
          <div className="sidebar-avatar-placeholder">{avatarInitial}</div>
          {!collapsed && (
            <div className="sidebar-profile-info">
              <p className="sidebar-profile-name">{displayName}</p>
              <span className="sidebar-profile-badge">{displayRole}</span>
            </div>
          )}
          {!collapsed && (
            <button
              className="sidebar-profile-more"
              aria-label="Menu akun"
              onClick={() => setMenuOpen((v) => !v)}
            >
              <MoreVertical size={16} />
            </button>
          )}
        </div>
      </div>

    </aside>
  );
}

export default Sidebar;