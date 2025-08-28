import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTimes } from '@fortawesome/free-solid-svg-icons';

export default function HowToPlayModal({ onClose }) {
    return (
        <div style={overlayStyle}>
            <div style={modalStyle}>
                <button style={closeButtonStyle} onClick={onClose}>
                    <FontAwesomeIcon icon={faTimes} />
                </button>
                <h2 style={titleStyle}>Cara Bermain</h2>
                <p style={subtitleStyle}>Yuk, pelajari dulu cara bermainnya agar kamu siap jadi pahlawan lingkungan</p>
                <ol style={listStyle}>
                    <li>Seret (drag) atau klik sampah yang muncul di layar.</li>
                    <li>Arahkan ke tempat sampah yang sesuai dengan jenisnya.</li>
                    <li>Setiap sampah yang dimasukkan dengan benar akan menambah poin.</li>
                    <li>Hati-hati! Memasukkan ke tempat yang salah akan mengurangi poin.</li>
                    <li>Selesaikan semua sampah dalam waktu yang tersedia.</li>
                </ol>
                <button style={backButtonStyle} onClick={onClose}>
                    Kembali
                </button>
            </div>
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const overlayStyle = {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    background: 'rgba(0, 0, 0, 0.6)',
    backdropFilter: 'blur(5px)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
};

const modalStyle = {
    background: 'white', // Latar belakang putih
    padding: '40px 60px',
    borderRadius: '20px',
    width: '450px',
    maxWidth: '90%',
    textAlign: 'center',
    color: '#1f2937', // Warna teks gelap
    fontFamily: "'Poppins', sans-serif",
    position: 'relative',
    boxShadow: '0 10px 25px rgba(0,0,0,0.3)',
};

const closeButtonStyle = {
    position: 'absolute',
    top: '15px',
    right: '15px',
    background: 'none',
    border: 'none',
    color: '#4b5563', // Warna ikon gelap
    fontSize: '24px',
    cursor: 'pointer',
};

const titleStyle = {
    fontSize: '2.5em',
    fontWeight: 'bold',
    marginBottom: '10px',
    color: '#16a34a', // Warna judul hijau
    textShadow: '1px 1px 2px rgba(0,0,0,0.1)',
};

const subtitleStyle = {
    fontSize: '1em',
    fontWeight: '300',
    marginBottom: '20px',
    color: '#4b5563', // Warna teks abu-abu gelap
};

const listStyle = {
    textAlign: 'left',
    fontSize: '1em',
    lineHeight: '1.8',
    marginBottom: '30px',
    paddingLeft: '20px',
    color: '#1f2937',
    listStyleType: 'decimal', // Penomoran
};

const backButtonStyle = {
    background: '#10b981', // Latar belakang tombol hijau
    color: 'white',
    padding: '12px 30px',
    border: 'none',
    borderRadius: '10px',
    cursor: 'pointer',
    fontSize: '1em',
    fontWeight: 'bold',
    transition: 'transform 0.2s ease',
    '&:hover': {
        transform: 'scale(1.05)',
    },
};
