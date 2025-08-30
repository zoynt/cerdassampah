import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faTrophy, faUser } from '@fortawesome/free-solid-svg-icons';

const dummyLeaderboard = [
    { username: 'User 1', total_points: 1000 },
    { username: 'User 2', total_points: 900 },
    { username: 'User 3', total_points: 800 },
    { username: 'User 4', total_points: 750 },
    { username: 'User 5', total_points: 700 },
    { username: 'User 6', total_points: 650 },
    { username: 'User 7', total_points: 600 },
    { username: 'User 8', total_points: 550 },
    { username: 'User 9', total_points: 500 },
    { username: 'User 10', total_points: 450 },
];

export default function LeaderboardSection() {
    const [leaderboardData, setLeaderboardData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

    useEffect(() => {
        const handleResize = () => setIsMobile(window.innerWidth < 768);
        window.addEventListener('resize', handleResize);

        const fetchLeaderboard = async () => {
            try {
                const response = await fetch('/api/leaderboard');
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                const data = await response.json();
                setLeaderboardData(data);
            } catch (error) {
                console.error('Error fetching leaderboard data:', error);
                setLeaderboardData(dummyLeaderboard);
            } finally {
                setLoading(false);
            }
        };

        fetchLeaderboard();
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    const rankedData = leaderboardData.map((item, index) => ({
        ...item,
        rank: index + 1
    }));

    const top3Rankings = rankedData.length > 0
        ? [rankedData[1], rankedData[0], rankedData[2]]
        : [];
    const otherRanks = rankedData.slice(3);

    const renderRankedList = (data) => (
        <div style={otherRanksContainerStyle}>
            {data.map((player) => (
                <div key={player.username} style={rankItemStyle}>
                    <span style={rankNumberStyle}>{player.rank}</span>
                    <div style={{display: 'flex', alignItems: 'center', gap: '10px', flex: 1}}>
                        <FontAwesomeIcon icon={faUser} style={rankIconStyle} />
                        <span style={usernameStyle}>{player.username}</span>
                    </div>
                    <span style={scoreStyle}>
                        <FontAwesomeIcon icon={faStar} style={{ color: '#facc15', marginRight: '5px' }} />
                        {player.total_points} Pts
                    </span>
                </div>
            ))}
        </div>
    );

    // Perbaikan: Menggunakan conditional margin-bottom
    const dynamicTitleMargin = isMobile ? '24px' : '64px';

    return (
        <div style={leaderboardSectionStyle}>
            <h2 style={{ ...leaderboardTitleStyle, marginBottom: dynamicTitleMargin }}>Leaderboard</h2>

            {loading ? (
                <p>Memuat Leaderboard...</p>
            ) : (
                <>
                    {!isMobile && (
                        <div style={top3ContainerStyle}>
                            {top3Rankings.map((player) => (
                                <div key={player.username} style={top3CardStyle(player.rank)}>
                                    <FontAwesomeIcon icon={faTrophy} style={trophyStyle(player.rank)} />
                                    <h3 style={rankNumberTop3Style}>{player.rank}</h3>
                                    <div style={usernameIconContainerStyle}>
                                        <FontAwesomeIcon icon={faUser} style={usernameIconStyle} />
                                    </div>
                                    <p style={usernameTop3Style}>{player.username}</p>
                                    <p style={scoreTop3Style}>{player.total_points} Points</p>
                                </div>
                            ))}
                        </div>
                    )}

                    {renderRankedList(isMobile ? rankedData : otherRanks)}
                </>
            )}
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const leaderboardSectionStyle = {
    width: '100%',
    padding: 'min(50px, 5vw) min(70px, 8vw)',
    background: 'white',
    borderRadius: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    textAlign: 'center',
    boxSizing: 'border-box',
};
const leaderboardTitleStyle = {
    color: '#16a34a',
    fontSize: 'min(5vw, 2em)',
    fontWeight: '800',
    textShadow: '1px 1px 2px rgba(0,0,0,0.1)'
};
const top3ContainerStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'flex-end',
    gap: 'min(20px, 3vw)',
    marginBottom: '20px',
    flexWrap: 'wrap',
};
const top3CardStyle = (rank) => ({
    width: 'min(120px, 25vw)',
    padding: '16px 12px',
    background: rank === 1 ? '#b91c1c' : rank === 2 ? '#a16207' : '#57534e',
    color: 'white',
    borderRadius: '12px',
    position: 'relative',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    minHeight: rank === 1 ? 'min(220px, 35vw)' : rank === 2 ? 'min(200px, 30vw)' : 'min(180px, 25vw)',
    justifyContent: 'space-between',
    boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`,
    transition: 'all 0.3s ease',
    flex: '1 1 auto',
});
const rankNumberTop3Style = {
    margin: '0',
    fontSize: 'min(6vw, 2.5em)',
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
};
const usernameIconStyle = {
    fontSize: '2em',
    color: 'white',
};
const usernameTop3Style = {
    margin: '0',
    fontWeight: 'bold',
    fontSize: 'min(3vw, 1em)'
};
const scoreTop3Style = {
    margin: '4px 0 0',
    fontSize: 'min(2.5vw, 0.8em)',
    color: '#facc15'
};
const trophyStyle = (rank) => ({
    fontSize: 'min(7vw, 3em)',
    color: rank === 1 ? '#facc15' : rank === 2 ? '#e5e7eb' : '#a16207',
    position: 'absolute',
    top: 'min(-4vw, -20px)',
    left: '50%',
    transform: 'translateX(-50%)',
    filter: 'drop-shadow(0 2px 2px rgba(0,0,0,0.2))'
});
const otherRanksContainerStyle = {
    background: 'white',
    padding: '10px',
    borderRadius: '12px',
    border: '1px solid rgba(0,0,0,0.1)',
};
const rankItemStyle = {
    display: 'flex',
    alignItems: 'center',
    background: 'rgba(243, 244, 246, 0.5)',
    borderRadius: '8px',
    padding: '10px 15px',
    justifyContent: 'space-between',
    marginBottom: '5px',
    flexWrap: 'wrap',
};
const rankNumberStyle = {
    fontWeight: 'bold',
    color: '#1f2937',
    width: '20px',
    textAlign: 'center'
};
const usernameStyle = {
    flex: 1,
    textAlign: 'left',
    color: '#1f2937',
    fontWeight: 'bold',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
};
const rankIconStyle = {
    fontSize: '1em',
    color: '#1f2937'
};
const scoreStyle = {
    fontWeight: 'bold',
    color: '#facc15',
    fontSize: '0.9em',
};
