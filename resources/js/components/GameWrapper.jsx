import React, { useState } from 'react';
import PilahSampahGame from './PilahSampahGame';
import StartScreen from './StartScreen';
import EndScreen from './EndScreen';

export default function GameWrapper() {
    const [gameState, setGameState] = useState('start');
    const [finalScore, setFinalScore] = useState(0);
    const [gameKey, setGameKey] = useState(0);

    const handleStartGame = () => {
        setGameState('playing');
        setGameKey(prevKey => prevKey + 1);
    };

    // Perubahan: Fungsi ini sekarang akan langsung mengarahkan ke halaman awal
    const handleEndGame = () => {
        setFinalScore(0); // Reset skor
        setGameState('start'); // Kembali ke halaman awal
    };

    const handleRestartGame = () => {
        setGameState('start');
        setFinalScore(0);
    };

    const gameProps = {
        onGameEnd: handleEndGame, // Teruskan fungsi baru
    };

    switch (gameState) {
        case 'playing':
            return <PilahSampahGame key={gameKey} {...gameProps} />;
        case 'end':
            return <EndScreen score={finalScore} onRestart={handleRestartGame} />;
        case 'start':
        default:
            return <StartScreen onStart={handleStartGame} />;
    }
}
