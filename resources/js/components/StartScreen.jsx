import React, { useState } from 'react';
import HeroSection from './HeroSection';
import LeaderboardSection from './LeaderboardSection';
import RewardSection from './RewardSection';
import HowToPlayModal from './HowToPlayModal';
import TrashTypeModal from './TrashTypeModal';

export default function StartScreen({ onStart }) {
    const [showHowToPlayModal, setShowHowToPlayModal] = useState(false);
    const [showTrashTypeModal, setShowTrashTypeModal] = useState(false);

    const handleShowHowToPlayModal = () => setShowHowToPlayModal(true);
    const handleCloseHowToPlayModal = () => setShowHowToPlayModal(false);

    const handleShowTrashTypeModal = () => setShowTrashTypeModal(true);
    const handleCloseTrashTypeModal = () => setShowTrashTypeModal(false);

    return (
        <div style={containerStyle}>
            <HeroSection
                onStart={onStart}
                onShowHowToPlay={handleShowHowToPlayModal}
                onShowTrashType={handleShowTrashTypeModal}
            />
            <LeaderboardSection />
            <RewardSection />

            {showHowToPlayModal && <HowToPlayModal onClose={handleCloseHowToPlayModal} />}
            {showTrashTypeModal && <TrashTypeModal onClose={handleCloseTrashTypeModal} />}
        </div>
    );
}

const containerStyle = {
    fontFamily: "'Poppins', sans-serif",
    width: '100%',
    boxSizing: 'border-box',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    gap: '24px',
};
