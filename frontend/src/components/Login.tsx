
import React, { useState } from 'react';
import axios from 'axios';
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await axios.post('/hospital/login', { email, password });
      window.location.href = '/dashboard';
    } catch (err: any) {
      setError('ログインに失敗しました。メールアドレスまたはパスワードが正しくありません。');
    }
  };

  return (
    <div className="login-container">
      <div className="login-box">
        <h2>ログイン</h2>
        {error && <div className="error-message">{error}</div>}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>メールアドレス</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div className="form-group">
            <label>パスワード</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <button type="submit">ログイン</button>
        </form>
      </div>
    </div>
  );
};

export default Login;
