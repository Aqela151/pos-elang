import { createContext, useContext, useState, useEffect } from "react";
import api from "../services/api";

const SettingsContext = createContext(null);

const LOCAL_STORAGE_SETTINGS_KEY = "pos_settings";

export function SettingsProvider({ children }) {
  const [settings, setSettings] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const load = async () => {
      try {
        const res = await api.get("/settings");
        setSettings(res.data || {});
        try {
          window.localStorage.setItem(LOCAL_STORAGE_SETTINGS_KEY, JSON.stringify(res.data || {}));
        } catch {}
      } catch (err) {
        const saved = window.localStorage.getItem(LOCAL_STORAGE_SETTINGS_KEY);
        if (saved) {
          try {
            setSettings(JSON.parse(saved));
          } catch {
            setSettings({});
          }
        } else {
          setSettings({});
        }
      } finally {
        setLoading(false);
      }
    };

    load();
  }, []);

  const refresh = async () => {
    try {
      const res = await api.get("/settings");
      setSettings(res.data || {});
      try {
        window.localStorage.setItem(LOCAL_STORAGE_SETTINGS_KEY, JSON.stringify(res.data || {}));
      } catch {}
      return true;
    } catch (err) {
      return false;
    }
  };

  return (
    <SettingsContext.Provider value={{ settings, setSettings, loading, refresh }}>
      {children}
    </SettingsContext.Provider>
  );
}

export function useSettings() {
  const ctx = useContext(SettingsContext);
  if (!ctx) throw new Error("useSettings must be used inside SettingsProvider");
  return ctx;
}

export default SettingsContext;
