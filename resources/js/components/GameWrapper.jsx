import React, { useState, useEffect } from 'react';
import PilahSampahGame from './PilahSampahGame';
import StartScreen from './StartScreen';
import EndScreen from './EndScreen';
import FullscreenPrompt from './FullscreenPrompt'; // <-- Tambahkan ini

export default function GameWrapper() {
    const [gameState, setGameState] = useState('start');
    const [finalScore, setFinalScore] = useState(0);
    const [gameKey, setGameKey] = useState(0);
    const [isMobile, setIsMobile] = useState(window.innerWidth < 768);
    const [isLandscape, setIsLandscape] = useState(window.innerWidth > window.innerHeight);

    // Effect untuk mendeteksi perubahan ukuran dan orientasi layar
    useEffect(() => {
        const handleResize = () => {
            setIsMobile(window.innerWidth < 768);
            setIsLandscape(window.innerWidth > window.innerHeight);
        };
        window.addEventListener('resize', handleResize);
        window.addEventListener('orientationchange', handleResize);
        return () => {
            window.removeEventListener('resize', handleResize);
            window.removeEventListener('orientationchange', handleResize);
        };
    }, []);

    const handleStartGame = () => {
        setGameState('playing');
        setGameKey(prevKey => prevKey + 1);
    };

    const handleEndGame = () => {
        setFinalScore(0);
        setGameState('start');
    };

    const handleRestartGame = () => {
        setGameState('start');
        setFinalScore(0);
    };

    const handleFullscreen = () => {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        }

        if (screen.orientation.lock) {
            screen.orientation.lock('landscape').catch(err => {
                console.error('Gagal mengunci orientasi layar:', err);
            });
        }
    };

    const gameProps = {
        onGameEnd: handleEndGame,
    };

    const wrapperStyle = {
        background: 'linear-gradient(to bottom, #dcfce7, #f0fdf4)',
        minHeight: '100vh',
        fontFamily: "'Poppins', sans-serif",
        padding: '24px',
        boxSizing: 'border-box',
    };

    if (gameState === 'playing' && isMobile && !isLandscape) {
        return <FullscreenPrompt onContinue={handleFullscreen} />;
    }

    switch (gameState) {
        case 'playing':
            return (
                <div style={wrapperStyle}>
                    <PilahSampahGame key={gameKey} {...gameProps} />
                </div>
            );
        case 'end':
            return (
                <div style={wrapperStyle}>
                    <EndScreen score={finalScore} onRestart={handleRestartGame} />
                </div>
            );
        case 'start':
        default:
            return (
                <div style={wrapperStyle}>
                    <StartScreen onStart={handleStartGame} />
                </div>
            );
    }
}
