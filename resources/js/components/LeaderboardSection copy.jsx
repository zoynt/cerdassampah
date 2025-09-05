import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faTrophy } from '@fortawesome/free-solid-svg-icons';

const Avatar = ({ player }) => {
  const url =
    player?.avatar_url
      ?? (player?.profile_photo_path ? `/storage/${player.profile_photo_path}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(player?.name || 'User')}&background=random`);

  return (
    <img
      src={url}
      alt={player?.name || 'User'}
      style={{
        width: 40,
        height: 40,
        borderRadius: '50%',
        objectFit: 'cover',
        border: '2px solid #eee',
      }}
      onError={(e) => {
        e.currentTarget.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(player?.name || 'User')}&background=random`;
      }}
    />
  );
};

export default function LeaderboardSection({ leaderboardData }) {
  if (!Array.isArray(leaderboardData) || leaderboardData.length === 0) {
    return <p>No leaderboard data available</p>;
  }

  // tambah rank berdasar urutan
  const rankedData = leaderboardData.map((item, index) => ({
    ...item,
    rank: index + 1,
  }));

  const top3 = rankedData.slice(0, 3);
  const top3Rankings = top3.length === 3 ? [top3[1], top3[0], top3[2]] : top3; // tampil 2-1-3
  const otherRanks = rankedData.slice(3);

  const getPoints = (p) => {
    // userpoints bisa array kosong/null; ambil elemen pertama jika ada
    const pts = Array.isArray(p?.userpoints) && p.userpoints.length > 0
      ? p.userpoints[0]?.points
      : 0;
    return Number(pts) || 0;
  };

  return (
    <div style={leaderboardSectionStyle}>
      <h2 style={leaderboardTitleStyle}>Leaderboard</h2>

      {/* Top 3 */}
      {top3Rankings.length > 0 && (
        <div style={top3ContainerStyle}>
          {top3Rankings.map((player) => (
            <div key={player.rank} style={top3CardStyle(player.rank)}>
              <FontAwesomeIcon icon={faTrophy} style={trophyStyle(player.rank)} />
              <h3 style={rankNumberTop3Style}>{player.rank}</h3>

              <div style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
                <Avatar player={player} />
                <p style={usernameTop3Style}>{player.name}</p>
              </div>

              <p style={scoreTop3Style}>{getPoints(player)} Points</p>
            </div>
          ))}
        </div>
      )}

      {/* Other ranks */}
      <div style={otherRanksContainerStyle}>
        {otherRanks.map((player) => (
          <div key={player.rank} style={rankItemStyle}>
            <span style={rankNumberStyle}>{player.rank}</span>

            <div style={{ display: 'flex', alignItems: 'center', gap: 10, flex: 1 }}>
              <Avatar player={player} />
              <span style={usernameStyle}>{player.name}</span>
            </div>

            <span style={scoreStyle}>
              <FontAwesomeIcon icon={faStar} style={{ color: '#facc15', marginRight: 5 }} />
              {getPoints(player)} Pts
            </span>
          </div>
        ))}
      </div>
    </div>
  );
}

/* --- Styling --- */
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
  textShadow: '1px 1px 2px rgba(0,0,0,0.1)',
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

const rankNumberTop3Style = { margin: 0, fontSize: 'min(6vw, 2.5em)', fontWeight: '800' };
const usernameTop3Style = { margin: 0, fontWeight: 'bold', fontSize: 'min(3vw, 1em)' };
const scoreTop3Style = { margin: '4px 0 0', fontSize: 'min(2.5vw, 0.8em)', color: '#facc15' };

const trophyStyle = (rank) => ({
  fontSize: 'min(7vw, 3em)',
  color: rank === 1 ? '#facc15' : rank === 2 ? '#e5e7eb' : '#a16207',
  position: 'absolute',
  top: 'min(-4vw, -20px)',
  left: '50%',
  transform: 'translateX(-50%)',
  filter: 'drop-shadow(0 2px 2px rgba(0,0,0,0.2))',
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

const rankNumberStyle = { fontWeight: 'bold', color: '#1f2937', width: 20, textAlign: 'center' };

const usernameStyle = {
  flex: 1,
  textAlign: 'left',
  color: '#1f2937',
  fontWeight: 'bold',
  whiteSpace: 'nowrap',
  overflow: 'hidden',
  textOverflow: 'ellipsis',
};

const scoreStyle = { fontWeight: 'bold', color: '#facc15', fontSize: '0.9em' };
