import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGift } from '@fortawesome/free-solid-svg-icons';

export default function RewardSection() {
    const dummyRewards = [
        { id: 1, name: 'Reward 1', points: 1000 },
        { id: 2, name: 'Reward 2', points: 2000 },
        { id: 3, name: 'Reward 3', points: 3000 },
    ];

    return (
        <div style={rewardSectionStyle}>
            <h2 style={{ color: '#16a34a', fontSize: '2em', fontWeight: '800', marginBottom: '20px' }}>Klaim Reward</h2>
            <div style={rewardBoxesContainerStyle}>
                {dummyRewards.map((reward) => (
                    <div key={reward.id} style={rewardBoxStyle}>
                        <FontAwesomeIcon icon={faGift} style={giftIconStyle} />
                        <p style={{ margin: '0', fontWeight: 'bold' }}>{reward.name}</p>
                        <p style={{ margin: '4px 0', fontSize: '0.9em', color: '#6b7280' }}>{reward.points} pts</p>
                        <button style={claimButtonStyle}>Klaim</button>
                    </div>
                ))}
            </div>
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const rewardSectionStyle = {
    // Menyamakan container dengan Hero dan Leaderboard
    width: '100%',
    maxWidth: '960px',
    backgroundColor: 'white',
    borderRadius: '20px',
    padding: '50px 70px',
    marginBottom: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    textAlign: 'center',
};
const rewardBoxesContainerStyle = {
    display: 'flex',
    justifyContent: 'space-around',
    gap: '20px',
    marginTop: '20px',
};
const rewardBoxStyle = {
    flex: 1,
    background: 'transparent',
    borderRadius: '12px',
    padding: '20px',
    border: '1px solid #e5e7eb',
    textAlign: 'center',
    boxShadow: '0 1px 3px rgba(0,0,0,0.1)',
};
const giftIconStyle = {
    fontSize: '3em',
    color: '#10b981', // Mengubah warna ikon menjadi lebih gelap
    marginBottom: '10px',
};
const claimButtonStyle = {
    marginTop: '10px',
    padding: '8px 16px',
    background: '#10b981',
    color: 'white',
    border: 'none',
    borderRadius: '6px',
    cursor: 'pointer',
    fontWeight: 'bold',
    transition: 'background-color 0.3s ease',
    '&:hover': {
        backgroundColor: '#059669',
    },
};
