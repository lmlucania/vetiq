
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Login.css';
import {axiosApi} from "../api/axiosApi";

const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await axiosApi.get('/sanctum/csrf-cookie');

      const response = await axiosApi.post(
          '/hospital/login',
          { email, password },

          );
      if (response.data) {
        navigate('/dashboard');
      }
    } catch (err: any) {
      if (err.response?.status === 401) {
        setError('メールアドレスまたはパスワードが正しくありません。');
      } else if (err.response?.data?.message) {
        setError(err.response.data.message);
      } else {
        setError('ログインに失敗しました。しばらく経ってから再度お試しください。');
      }
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
