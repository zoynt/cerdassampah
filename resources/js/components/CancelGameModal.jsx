import React from 'react';

const CancelGameModal = ({ onCancel, onContinue }) => {
    const overlayStyle = {
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        background: 'rgba(17, 24, 39, 0.6)',
        backdropFilter: 'blur(4px)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 400,
    };

    const modalStyle = {
        background: 'white',
        padding: '32px',
        borderRadius: '16px',
        textAlign: 'center',
        boxShadow: '0 25px 50px -12px rgb(0 0 0 / 0.25)',
        width: '450px', // DIUBAH: Lebar modal menjadi 500px
        maxWidth: '90%',
    };

    const titleStyle = {
        fontSize: '22px',
        fontWeight: '600',
        color: '#1f2937',
        margin: '0 0 12px 0',
    };

    const textStyle = {
        color: '#4b5563',
        margin: '0 0 24px 0',
    };

    const buttonContainerStyle = {
        display: 'flex',
        gap: '12px',
        justifyContent: 'center',
    };

    const buttonStyle = {
        padding: '12px 24px', // DIUBAH: Padding tombol lebih besar
        fontSize: '16px',
        fontWeight: '700', // DIUBAH: Membuat teks tombol menjadi bold
        border: 'none',
        borderRadius: '8px',
        cursor: 'pointer',
        transition: 'background-color 0.2s ease, transform 0.1s ease', // Menambahkan transisi
    };

    const cancelButtonStyle = {
        ...buttonStyle,
        background: '#fee2e2',
        color: '#dc2626',
        '&:hover': {
            background: '#fecaca', // Efek hover
            transform: 'translateY(-2px)', // Efek kecil saat hover
        }
    };

    const continueButtonStyle = {
        ...buttonStyle,
        background: '#dcfce7',
        color: '#166534',
        '&:hover': {
            background: '#a7f3d0', // Efek hover
            transform: 'translateY(-2px)', // Efek kecil saat hover
        }
    };

    return (
        <div style={overlayStyle}>
            <div style={modalStyle}>
                <h2 style={titleStyle}>Keluar dari Permainan?</h2>
                <p style={textStyle}>Apakah Anda yakin ingin keluar? Progres permainan ini akan hilang.</p>
                <div style={buttonContainerStyle}>
                    <button
                        style={cancelButtonStyle}
                        onClick={onCancel}
                        // Menambahkan onMouseEnter dan onMouseLeave untuk efek hover
                        onMouseEnter={(e) => e.currentTarget.style.backgroundColor = cancelButtonStyle['&:hover'].background}
                        onMouseLeave={(e) => e.currentTarget.style.backgroundColor = cancelButtonStyle.background}
                        onMouseDown={(e) => e.currentTarget.style.transform = 'translateY(0)'} // Efek klik
                        onMouseUp={(e) => e.currentTarget.style.transform = 'translateY(-2px)'}
                    >
                        Ya, Keluar
                    </button>
                    <button
                        style={continueButtonStyle}
                        onClick={onContinue}
                        onMouseEnter={(e) => e.currentTarget.style.backgroundColor = continueButtonStyle['&:hover'].background}
                        onMouseLeave={(e) => e.currentTarget.style.backgroundColor = continueButtonStyle.background}
                        onMouseDown={(e) => e.currentTarget.style.transform = 'translateY(0)'}
                        onMouseUp={(e) => e.currentTarget.style.transform = 'translateY(-2px)'}
                    >
                        Lanjutkan Bermain
                    </button>
                </div>
            </div>
        </div>
    );
};

export default CancelGameModal;
