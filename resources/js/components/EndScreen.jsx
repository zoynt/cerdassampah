import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTimes, faGift, faStar, faTrophy, faUser } from '@fortawesome/free-solid-svg-icons';
import LeaderboardSection from './LeaderboardSection';

// Data dummy untuk leaderboard (sementara, dapat diganti)
const dummyLeaderboard = [
    { rank: 1, username: 'User1', score: 1000 },
    { rank: 2, username: 'User2', score: 900 },
    { rank: 3, username: 'User3', score: 800 },
    { rank: 4, username: 'User4', score: 750 },
    { rank: 5, username: 'User5', score: 700 },
    { rank: 6, username: 'User6', score: 650 },
    { rank: 7, username: 'User7', score: 600 },
    { rank: 8, username: 'User8', score: 550 },
    { rank: 9, username: 'User9', score: 500 },
    { rank: 10, username: 'User10', score: 450 },
];

export default function EndScreen({ score, onRestart }) {
    const dummyRewards = [
        { id: 1, name: 'Reward 1', points: 1000 },
        { id: 2, name: 'Reward 2', points: 2000 },
        { id: 3, name: 'Reward 3', points: 3000 },
    ];

    return (
        <div style={endScreenContainerStyle}>
            <div style={headerStyle}>
                {/* Tombol X yang mengembalikan ke halaman awal */}
                <button style={backButtonEndScreenStyle} onClick={onRestart}>
                    <FontAwesomeIcon icon={faTimes} />
                </button>
            </div>

            <div style={leaderboardSectionStyle}>
                <h2 style={{ marginBottom: '20px', color: '#111' }}>Leaderboard</h2>

                {/* Ranking Top 3 */}
                <div style={top3ContainerStyle}>
                    {dummyLeaderboard.slice(0, 3).map((player, index) => (
                        <div key={player.rank} style={top3CardStyle(player.rank)}>
                            <FontAwesomeIcon icon={faTrophy} style={trophyStyle(player.rank)} />
                            <h3 style={rankNumberTop3Style}>{player.rank}</h3>
                            <div style={usernameIconContainerStyle}>
                                <FontAwesomeIcon icon={faUser} style={usernameIconStyle} />
                            </div>
                            <p style={usernameTop3Style}>{player.username}</p>
                            <p style={scoreTop3Style}>{player.score} Points</p>
                        </div>
                    ))}
                </div>

                {/* Ranking 4-10 */}
                <div style={otherRanksContainerStyle}>
                    {dummyLeaderboard.slice(3).map((player) => (
                        <div key={player.rank} style={rankItemStyle}>
                            <span style={rankNumberStyle}>{player.rank}</span>
                            <div style={{display: 'flex', alignItems: 'center', gap: '10px', flex: 1}}>
                                <FontAwesomeIcon icon={faUser} style={rankIconStyle} />
                                <span style={usernameStyle}>{player.username}</span>
                            </div>
                            <span style={scoreStyle}>
                                <FontAwesomeIcon icon={faStar} style={{ color: '#facc15', marginRight: '5px' }} />
                                {player.score} Pts
                            </span>
                        </div>
                    ))}
                </div>
            </div>

            <div style={rewardSectionStyle}>
                <h2 style={{ color: '#333' }}>Klaim Reward</h2>
                <div style={rewardBoxesContainerStyle}>
                    {dummyRewards.map((reward) => (
                        <div key={reward.id} style={rewardBoxStyle}>
                            <FontAwesomeIcon icon={faGift} style={giftIconStyle} />
                            <p style={{ margin: '0' }}>{reward.name}</p>
                            <p style={{ margin: '4px 0' }}>{reward.points} pts</p>
                            <button style={claimButtonStyle}>Klaim</button>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const endScreenContainerStyle = {
    background: 'linear-gradient(to bottom, #dcfce7, #f0fdf4)',
    padding: '40px',
    borderRadius: '24px',
    fontFamily: "'Poppins', sans-serif",
    height: '100%',
    boxSizing: 'border-box',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    boxShadow: '0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)'
};
const headerStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'flex-end',
    width: '100%',
    marginBottom: '20px',
};
const backButtonEndScreenStyle = {
    background: '#fee2e2',
    color: '#ef4444',
    border: 'none',
    width: '40px',
    height: '40px',
    borderRadius: '50%',
    cursor: 'pointer',
    fontSize: '18px',
};
const leaderboardSectionStyle = {
    borderRadius: '16px',
    padding: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    marginBottom: '20px',
    textAlign: 'center',
    backgroundColor: '#f0fdf4',
    width: '100%',
    maxWidth: '960px',
};
const top3ContainerStyle = {
    display: 'flex',
    justifyContent: 'space-around',
    alignItems: 'flex-end',
    marginBottom: '20px',
};
const top3CardStyle = (rank) => ({
    width: '120px',
    padding: '16px 12px',
    background: rank === 1 ? '#b91c1c' : rank === 2 ? '#a16207' : '#57534e',
    color: 'white',
    borderRadius: '12px',
    position: 'relative',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    transform: rank === 1 ? 'scale(1.15) translateY(-20px)' : 'scale(1)',
    zIndex: rank === 1 ? 10 : 1,
    boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`,
});
const rankNumberTop3Style = {
    margin: '0',
    fontSize: '2.5em',
    fontWeight: '800'
};
const usernameIconContainerStyle = {
    width: '50px',
    height: '50px',
    borderRadius: '50%',
    background: 'rgba(255,255,255,0.2)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: '8px'
};
const usernameIconStyle = {
    fontSize: '2em',
    color: 'white',
};
const usernameTop3Style = {
    margin: '0',
    fontWeight: 'bold',
    fontSize: '1em'
};
const scoreTop3Style = {
    margin: '4px 0 0',
    fontSize: '0.8em',
    color: '#facc15'
};
const trophyStyle = (rank) => ({
    fontSize: '3em',
    color: rank === 1 ? '#facc15' : rank === 2 ? '#e5e7eb' : '#a16207',
    position: 'absolute',
    top: '-20px',
    left: '50%',
    transform: 'translateX(-50%)',
    filter: 'drop-shadow(0 2px 2px rgba(0,0,0,0.2))'
});
const otherRanksContainerStyle = {
    background: '#fee2e2',
    padding: '10px',
    borderRadius: '12px',
    border: '2px solid #ef4444'
};
const rankItemStyle = {
    display: 'flex',
    alignItems: 'center',
    background: '#fef2f2',
    borderRadius: '8px',
    padding: '10px 15px',
    justifyContent: 'space-between',
    marginBottom: '5px'
};
const rankNumberStyle = {
    fontWeight: 'bold',
    color: '#6b7280',
    width: '20px',
    textAlign: 'center'
};
const usernameStyle = {
    flex: 1,
    textAlign: 'left',
    color: '#333',
    fontWeight: 'bold'
};
const rankIconStyle = {
    fontSize: '1em',
    color: '#9ca3af'
};
const scoreStyle = {
    fontWeight: 'bold',
    color: '#facc15',
    fontSize: '0.9em'
};
const rewardSectionStyle = {
    background: 'white',
    borderRadius: '16px',
    padding: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    textAlign: 'center',
    backgroundColor: '#f0fdf4',
    width: '100%',
    maxWidth: '960px',
    marginTop: '20px',
};
const rewardBoxesContainerStyle = {
    display: 'flex',
    justifyContent: 'space-around',
    gap: '20px',
    marginTop: '20px',
};
const rewardBoxStyle = {
    flex: 1,
    background: '#f9fafb',
    borderRadius: '12px',
    padding: '20px',
    border: '1px solid #e5e7eb',
    textAlign: 'center',
};
const giftIconStyle = {
    fontSize: '3em',
    color: '#22c55e',
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
};
