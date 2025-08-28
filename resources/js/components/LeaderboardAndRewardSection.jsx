import React from 'react';

export default function LeaderboardAndRewardSection() {
    // Sesi ini akan diisi setelah Anda mengirimkan desain
    return (
        <div style={sectionStyle}>
            <h2>Leaderboard</h2>
            <p>Silakan kirimkan desain untuk bagian ini.</p>

            <h2 style={{marginTop: '40px'}}>Klaim Reward</h2>
            <p>Silakan kirimkan desain untuk bagian ini.</p>
        </div>
    );
}

const sectionStyle = {
    width: '100%',
    maxWidth: '960px',
    padding: '40px',
    textAlign: 'center',
};
