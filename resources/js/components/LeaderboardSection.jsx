import React, { useEffect, useState, useMemo } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faTrophy, faUser } from '@fortawesome/free-solid-svg-icons';

const Avatar = ({ player }) => {
  const label =  player?.username || 'User';
  const url =
    player?.avatar_url
      ?? (player?.profile_photo_path
            ? `/storage/${String(player.profile_photo_path).replace(/^\/+/, '')}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(label)}&background=random`);

  return (
    <img
      src={url}
      alt={label}
      style={{ width: 40, height: 40, borderRadius: '50%', objectFit: 'cover' }}
      onError={(e) => {
        e.currentTarget.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(label)}&background=random`;
      }}
    />
  );
};

export default function LeaderboardSection({ leaderboardData }) {
  const [isMobile, setIsMobile] = useState(
    typeof window !== 'undefined' ? window.innerWidth < 768 : false
  );

  useEffect(() => {
    const onResize = () => setIsMobile(window.innerWidth < 768);
    if (typeof window !== 'undefined') {
      window.addEventListener('resize', onResize);
      return () => window.removeEventListener('resize', onResize);
    }
  }, []);

  if (!Array.isArray(leaderboardData) || leaderboardData.length === 0) {
    return <p>No leaderboard data available</p>;
  }

  const getPoints = (u) => {
    // userpoints = hasMany; ambil elemen pertama jika ada
    const val = Array.isArray(u?.userpoints) && u.userpoints.length > 0
      ? u.userpoints[0]?.points
      : 0;
    return Number(val) || 0;
  };

  // urutkan lagi di front-end jika perlu (jaga-jaga)
  const sorted = useMemo(() => {
    return [...leaderboardData].sort((a, b) => getPoints(b) - getPoints(a));
  }, [leaderboardData]);

  const rankedData = useMemo(() => {
    return sorted.map((item, index) => ({ ...item, rank: index + 1 }));
  }, [sorted]);

  const top3 = rankedData.slice(0, 3);
  const top3Rankings =
    top3.length === 3 ? [top3[1], top3[0], top3[2]] : top3; // susun 2-1-3 jika lengkap
  const otherRanks = rankedData.slice(3);

  const dynamicTitleMargin = isMobile ? '24px' : '64px';

  const renderRankedList = (data) => (
    <div style={otherRanksContainerStyle}>
      {data.map((player) => {
        const label =  player?.username || 'User';

  console.log('User:', label, 'Userpoints:', player.userpoints);

        return (
          <div key={`${player.id ?? label}-${player.rank}`} style={rankItemStyle}>
            <span style={rankNumberStyle}>{player.rank}</span>
            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flex: 1 }}>
              {/* ganti ikon dengan foto profil */}
              <Avatar player={player} />
              <span style={usernameStyle}>{label}</span>
            </div>
            <span style={scoreStyle}>
              <FontAwesomeIcon icon={faStar} style={{ color: '#facc15', marginRight: '5px' }} />
              {getPoints(player)} Pts
            </span>
          </div>
        );
      })}
    </div>
  );

  return (
    <div style={leaderboardSectionStyle}>
      <h2 style={{ ...leaderboardTitleStyle, marginBottom: dynamicTitleMargin }}>Leaderboard</h2>

      {/* Top 3 */}
      {!isMobile && top3Rankings.length > 0 && (
        <div style={top3ContainerStyle}>
          {top3Rankings.map((player) => {
            const label =  player?.username || 'User';
            return (
              <div key={`${player.id ?? label}-top-${player.rank}`} style={top3CardStyle(player.rank)}>
                <FontAwesomeIcon icon={faTrophy} style={trophyStyle(player.rank)} />
                <h3 style={rankNumberTop3Style}>{player.rank}</h3>

                <div style={usernameIconContainerStyle}>
                  <Avatar player={player} />
                </div>

                <p style={usernameTop3Style}>{label}</p>
                <p style={scoreTop3Style}>{getPoints(player)} Points</p>
              </div>
            );
          })}
        </div>
      )}

      {/* List lainnya */}
      {renderRankedList(isMobile ? rankedData : otherRanks)}
      
    </div>
  );
}

/* --- STYLING (CSS-in-JS) --- */
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
const usernameIconContainerStyle = {
  width: '50px',
  height: '50px',
  borderRadius: '50%',
  background: 'rgba(255,255,255,0.2)',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  overflow: 'hidden',
};
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
const rankNumberStyle = { fontWeight: 'bold', color: '#1f2937', width: '20px', textAlign: 'center' };
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
