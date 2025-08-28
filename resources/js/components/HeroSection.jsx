import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlay, faInfoCircle, faRecycle } from '@fortawesome/free-solid-svg-icons';

export default function HeroSection({ onStart, onShowHowToPlay, onShowTrashType }) { // <-- Terima prop baru
    return (
            <div style={heroContentStyle}>
                <div style={imageContainerStyle}>
                    <img src="img/logo.png" alt="Trash Can Robot" style={{ height: '150px' }} />
                </div>
                <h1 style={gameTitleStyle}>Game Pilah Sampah</h1>
                <div style={buttonContainerStyle}>
                    <button style={secondaryButtonStyle} onClick={onShowHowToPlay}>
                        <FontAwesomeIcon icon={faInfoCircle} style={{marginRight: '8px'}} />
                        Cara bermain
                    </button>
                    <button style={primaryButtonStyle} onClick={onStart}>
                        <FontAwesomeIcon icon={faPlay} style={{marginRight: '8px'}} />
                        MULAI
                    </button>
                    <button style={secondaryButtonStyle} onClick={onShowTrashType}> {/* <-- Panggil fungsi baru di sini */}
                        <FontAwesomeIcon icon={faRecycle} style={{marginRight: '8px'}} />
                        Jenis Sampah
                    </button>
                </div>
            </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const heroContentStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    textAlign: 'center',
    width: '100%',
    maxWidth: '960px',
    border: '1px solid rgba(255, 255, 255, 0.2)',
    backdropFilter: 'blur(10px)',
    borderRadius: '20px',
    padding: '50px 70px',
    marginBottom: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    backgroundColor: 'white',
};

const imageContainerStyle = {
    marginBottom: '20px',
};

const gameTitleStyle = {
    fontSize: '3em',
    fontWeight: '800',
    marginBottom: '30px',
    textShadow: '2px 2px 4px rgba(0,0,0,0.1)',
    color: '#16a34a',
};

const buttonContainerStyle = {
    display: 'flex',
    gap: '20px',
    justifyContent: 'center',
};

const baseButtonStyle = {
    padding: '12px 24px',
    borderRadius: '12px',
    fontSize: '1em',
    fontWeight: 'bold',
    cursor: 'pointer',
    border: 'none',
    boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
    transition: 'transform 0.3s ease, box-shadow 0.3s ease',
    display: 'flex',
    alignItems: 'center',
    textTransform: 'uppercase',
};

const primaryButtonStyle = {
    ...baseButtonStyle,
    background: '#10b981',
    color: 'white',
    '&:hover': {
        transform: 'scale(1.05)',
        boxShadow: '0 6px 16px rgba(0,0,0,0.3)',
    },
};

const secondaryButtonStyle = {
    ...baseButtonStyle,
    background: 'white',
    color: '#1f2937',
    '&:hover': {
        transform: 'scale(1.05)',
        boxShadow: '0 6px 16px rgba(0,0,0,0.3)',
    },
};
