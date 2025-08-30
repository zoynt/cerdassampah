import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTimes } from '@fortawesome/free-solid-svg-icons';

export default function TrashTypeModal({ onClose }) {
    return (
        <div style={overlayStyle}>
            <div style={modalStyle}>
                <button style={closeButtonStyle} onClick={onClose}>
                    <FontAwesomeIcon icon={faTimes} />
                </button>
                <h2 style={titleStyle}>Jenis Sampah</h2>
                <p style={subtitleStyle}>Memisahkan sampah ke dalam tiga jenis tempat sampah yang benar</p>
                <ol style={listStyle}>
                    <li>
                        <div style={listItemHeaderStyle}><span style={{color: '#16a34a', fontWeight: 'bold'}}>Organik</span></div>
                        <p style={listItemDescriptionStyle}>Sampah yang bisa terurai, seperti sisa makanan, daun, kulit buah.</p>
                    </li>
                    <li>
                        <div style={listItemHeaderStyle}><span style={{color: '#f59e0b', fontWeight: 'bold'}}>Anorganik</span></div>
                        <p style={listItemDescriptionStyle}>Sampah yang sulit terurai, seperti plastik, botol minum, kertas.</p>
                    </li>
                    <li>
                        <div style={listItemHeaderStyle}><span style={{color: '#ef4444', fontWeight: 'bold'}}>B3</span> (Bahan Berbahaya & Beracun)</div>
                        <p style={listItemDescriptionStyle}>Sampah berbahaya seperti baterai, kaca pecah, obat kadaluarsa.</p>
                    </li>
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
    WebkitBackdropFilter: 'blur(5px)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
};

const modalStyle = {
    background: 'white',
    padding: '30px 40px',
    borderRadius: '20px',
    width: '400px',
    maxWidth: '90%',
    textAlign: 'center',
    color: '#1f2937',
    fontFamily: "'Poppins', sans-serif",
    position: 'relative',
    boxShadow: '0 10px 25px rgba(0,0,0,0.3)',
};

const closeButtonStyle = {
    position: 'absolute',
    top: '10px',
    right: '10px',
    background: 'none',
    border: 'none',
    color: '#4b5563',
    fontSize: '20px',
    cursor: 'pointer',
};

const titleStyle = {
    fontSize: '2em',
    fontWeight: 'bold',
    marginBottom: '8px',
    color: '#16a34a',
    textShadow: '1px 1px 2px rgba(0,0,0,0.1)',
};

const subtitleStyle = {
    fontSize: '0.9em',
    fontWeight: '300',
    marginBottom: '20px',
    color: '#4b5563',
};

const listStyle = {
    textAlign: 'left',
    fontSize: '0.9em',
    lineHeight: '1.8',
    marginBottom: '30px',
    paddingLeft: '20px',
    color: '#1f2937',
    listStyleType: 'decimal',
};

const listItemHeaderStyle = {
    display: 'block', // Mengubah display agar setiap judul memiliki baris sendiri
    fontWeight: 'bold',
};

const listItemDescriptionStyle = {
    display: 'block', // Mengubah display agar setiap deskripsi memiliki baris sendiri
    margin: 0,
    lineHeight: '1.4',
};

const backButtonStyle = {
    background: '#10b981',
    color: 'white',
    padding: '10px 24px',
    border: 'none',
    borderRadius: '8px',
    cursor: 'pointer',
    fontSize: '0.9em',
    fontWeight: 'bold',
    transition: 'transform 0.2s ease',
    '&:hover': {
        transform: 'scale(1.05)',
    },
};
