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
            <h2 style={{ color: '#16a34a', fontSize: 'min(5vw, 2em)', fontWeight: '800', marginBottom: '20px' }}>Klaim Reward</h2>
            <div style={rewardBoxesContainerStyle}>
                {dummyRewards.map((reward) => (
                    <div key={reward.id} style={rewardBoxStyle}>
                        <FontAwesomeIcon icon={faGift} style={giftIconStyle} />
                        <p style={{ margin: '0', fontWeight: 'bold', fontSize: 'min(3vw, 1em)' }}>{reward.name}</p>
                        <p style={{ margin: '4px 0', fontSize: 'min(2.5vw, 0.9em)', color: '#6b7280' }}>{reward.points} pts</p>
                        <button style={claimButtonStyle}>Klaim</button>
                    </div>
                ))}
            </div>
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const rewardSectionStyle = {
    width: '100%',
    padding: 'min(50px, 5vw) min(70px, 8vw)',
    backgroundColor: 'white',
    borderRadius: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    textAlign: 'center',
    boxSizing: 'border-box',
};
const rewardBoxesContainerStyle = {
    display: 'flex',
    justifyContent: 'center',
    gap: 'min(20px, 3vw)',
    marginTop: '20px',
    flexWrap: 'wrap',
};
const rewardBoxStyle = {
    flex: '1 1 min(200px, 25vw)',
    background: 'white',
    borderRadius: '12px',
    padding: '20px',
    border: '1px solid #e5e7eb',
    textAlign: 'center',
    boxShadow: '0 1px 3px rgba(0,0,0,0.1)',
};
const giftIconStyle = {
    fontSize: 'min(10vw, 3em)',
    color: '#10b981',
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
    fontSize: 'min(2.5vw, 0.9em)',
    '&:hover': {
        backgroundColor: '#059669',
    },
};
