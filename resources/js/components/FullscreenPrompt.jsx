import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faRotate } from '@fortawesome/free-solid-svg-icons';

export default function FullscreenPrompt({ onContinue }) {
    return (
        <div style={overlayStyle}>
            <div style={modalStyle}>
                <h2 style={titleStyle}>Putar Layar untuk Bermain!</h2>
                <FontAwesomeIcon icon={faRotate} style={iconStyle} />
                <p style={messageStyle}>
                    Untuk pengalaman terbaik, putar perangkat Anda ke mode lanskap dan klik tombol di bawah ini.
                </p>
                <button style={buttonStyle} onClick={onContinue}>
                    Mulai Fullscreen
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
    background: 'rgba(0,0,0,0.8)',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 9999,
};

const modalStyle = {
    background: 'white',
    padding: '40px',
    borderRadius: '20px',
    width: '350px',
    maxWidth: '90%',
    textAlign: 'center',
    fontFamily: "'Poppins', sans-serif",
    position: 'relative',
    boxShadow: '0 10px 25px rgba(0,0,0,0.3)',
};

const titleStyle = {
    fontSize: '1.8em',
    fontWeight: 'bold',
    marginBottom: '10px',
    color: '#1f2937',
};

const iconStyle = {
    fontSize: '5em',
    color: '#4b5563',
    margin: '20px 0',
    transform: 'rotate(90deg)',
};

const messageStyle = {
    fontSize: '0.9em',
    fontWeight: '400',
    marginBottom: '30px',
    color: '#4b5563',
    lineHeight: '1.5',
};

const buttonStyle = {
    padding: '12px 28px',
    borderRadius: '10px',
    fontSize: '1em',
    fontWeight: 'bold',
    cursor: 'pointer',
    border: 'none',
    background: '#10b981',
    color: 'white',
    transition: 'transform 0.2s ease, box-shadow 0.2s ease',
};
