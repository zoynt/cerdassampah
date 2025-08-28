import React from 'react';

export default function CancelGameModal({ onCancel, onContinue }) {
    return (
        <div style={overlayStyle}>
            <div style={modalStyle}>
                <h2 style={titleStyle}>Batalkan Permainan?</h2>
                <p style={messageStyle}>Apakah kamu yakin ingin membatalkan permainan?<br />Progresmu tidak akan disimpan.</p>
                <div style={buttonContainerStyle}>
                    <button style={cancelButtonStyle} onClick={onCancel}>
                        Ya, Batalkan
                    </button>
                    <button style={continueButtonStyle} onClick={onContinue}>
                        Lanjutkan Bermain
                    </button>
                </div>
            </div>
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const overlayStyle = {
    position: 'fixed', // <-- PERBAIKAN: Mengubah dari 'absolute' menjadi 'fixed'
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    background: 'rgba(17, 24, 39, 0.6)',
    backdropFilter: 'blur(4px)',
    WebkitBackdropFilter: 'blur(4px)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
};

const modalStyle = {
    background: 'white',
    padding: '40px',
    borderRadius: '20px',
    width: '550px',
    maxWidth: '90%',
    textAlign: 'center',
    fontFamily: "'Poppins', sans-serif",
    position: 'relative',
    boxShadow: '0 10px 25px rgba(0,0,0,0.3)',
};

const titleStyle = {
    fontSize: '2em',
    fontWeight: 'bold',
    marginBottom: '10px',
    color: '#1f2937',
};

const messageStyle = {
    fontSize: '0.9em',
    fontWeight: '300',
    marginBottom: '30px',
    color: '#4b5563',
    lineHeight: '1.5',
};

const buttonContainerStyle = {
    display: 'flex',
    gap: '20px',
    justifyContent: 'center',
};

const baseButtonStyle = {
    padding: '12px 28px',
    borderRadius: '10px',
    fontSize: '0.95em',
    fontWeight: 'bold',
    cursor: 'pointer',
    border: 'none',
    transition: 'transform 0.2s ease, box-shadow 0.2s ease',
};

const cancelButtonStyle = {
    ...baseButtonStyle,
    background: '#ef4444',
    color: 'white',
};

const continueButtonStyle = {
    ...baseButtonStyle,
    background: '#22c55e',
    color: 'white',
};
