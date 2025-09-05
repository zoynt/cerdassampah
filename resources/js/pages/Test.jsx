import React from 'react';
import { usePage } from '@inertiajs/react';
import GameWrapper from '../components/GameWrapper';

export default function Test() {
  const { leaderboard,  } = usePage().props; // Harusnya leaderboardData, jika tidak bisa coba console.log(usePage().props)

  return (
    <div>
        <GameWrapper leaderboardData={leaderboard} />
      {/* <GameWrapper /> */}

    </div>
  );
}
