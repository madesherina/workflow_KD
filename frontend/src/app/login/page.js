"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(false);
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";
      const res = await fetch(`${apiUrl}/api/v1/auth/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();

      if (!res.ok) {
        if (res.status === 422) {
          setError(data.message || "Validasi gagal. Periksa kembali input Anda.");
        } else if (res.status === 401) {
          setError("Email atau password salah.");
        } else {
          setError("Terjadi kesalahan pada server. Silakan coba lagi.");
        }
        setLoading(false);
        return;
      }

      // Success
      const token = data.token;
      
      // Penyimpanan Token: 
      // Menggunakan document.cookie agar token dapat dibaca oleh Next.js Middleware (Server-Side) 
      // di masa depan untuk route protection. LocalStorage tidak bisa dibaca oleh SSR/Middleware.
      // Untuk keamanan ekstra di production, idealnya token diset via Set-Cookie HTTP-Only dari backend,
      // namun karena arsitektur Sanctum SPA/Bearer murni, cookie client-side adalah kompromi terbaik saat ini.
      
      let cookieString = `auth_token=${token}; path=/; max-age=${60 * 60 * 24 * 7}; SameSite=Lax`;
      if (window.location.protocol === 'https:') {
          cookieString += '; Secure';
      }
      document.cookie = cookieString;

      alert("Login berhasil! (Halaman tujuan belum dibuat)");
      // router.push("/"); // Placeholder redirect

    } catch (err) {
      setError("Tidak dapat terhubung ke server.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-50">
      {/* Background approximation of Blade's dynamic green mesh */}
      <div className="absolute inset-0 z-0 bg-gradient-to-br from-green-100 via-emerald-50 to-teal-100 opacity-80"></div>
      <div className="absolute inset-0 z-10 bg-white/20 backdrop-blur-sm"></div>

      <div className="relative z-20 w-full max-w-[460px] p-10 sm:p-14 bg-white/70 backdrop-blur-2xl border border-white/40 rounded-[32px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)]">
        <div className="text-center mb-10">
          <h1 className="text-2xl font-extrabold text-slate-800 mb-2 tracking-tight">
            NEX<span className="text-green-500">PUBLISH</span>
          </h1>
          <p className="text-slate-500 text-sm font-medium">Workflow Management System</p>
        </div>

        <h2 className="text-[1.75rem] font-bold text-slate-800 text-center mb-2">Welcome Back</h2>
        <p className="text-center text-slate-500 mb-8 text-[0.95rem]">Please enter your details to sign in</p>

        {error && (
          <div className="bg-red-50 border border-red-200 p-3.5 rounded-xl mb-6 text-sm text-red-700 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" x2="12" y1="8" y2="12" />
              <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
            {error}
          </div>
        )}

        <form onSubmit={handleLogin}>
          <div className="mb-5">
            <label className="block font-semibold text-[0.85rem] text-slate-700 mb-2" htmlFor="email">
              Email Address
            </label>
            <input
              id="email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="name@company.com"
              required
              disabled={loading}
              className="w-full p-[0.85rem_1.1rem] rounded-xl border border-slate-200 bg-slate-50 text-[0.95rem] transition-all duration-200 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/15 disabled:opacity-50"
            />
          </div>

          <div className="mb-5">
            <label className="block font-semibold text-[0.85rem] text-slate-700 mb-2" htmlFor="password">
              Password
            </label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="••••••••"
              required
              disabled={loading}
              className="w-full p-[0.85rem_1.1rem] rounded-xl border border-slate-200 bg-slate-50 text-[0.95rem] transition-all duration-200 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/15 disabled:opacity-50"
            />
          </div>

          <div className="flex justify-between items-center mb-8 text-[0.85rem] text-slate-500">
            <label className="flex items-center gap-2 cursor-pointer select-none">
              <input
                type="checkbox"
                checked={remember}
                onChange={(e) => setRemember(e.target.checked)}
                className="w-4 h-4 rounded border-slate-300 cursor-pointer text-green-500 focus:ring-green-500"
              />
              <span>Remember me</span>
            </label>
            <a href="#" className="text-green-500 font-bold hover:underline">
              Forgot password?
            </a>
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full p-4 rounded-xl font-bold text-white bg-gradient-to-r from-green-500 to-green-600 shadow-[0_10px_15px_-3px_rgba(34,197,94,0.3)] transition-transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:hover:translate-y-0 flex justify-center items-center"
          >
            {loading ? (
              <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            ) : (
              "Sign In"
            )}
          </button>
        </form>

        <div className="mt-8 text-center text-slate-500 text-[0.85rem] font-medium">
          Don't have an account?{" "}
          <a href="#" className="text-green-500 font-bold hover:underline">
            Contact Admin
          </a>
        </div>
      </div>
    </div>
  );
}
