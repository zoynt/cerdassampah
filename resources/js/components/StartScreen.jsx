import React, { useState } from 'react';
import HeroSection from './HeroSection';
import LeaderboardSection from './LeaderboardSection';
import RewardSection from './RewardSection';
import HowToPlayModal from './HowToPlayModal';
import TrashTypeModal from './TrashTypeModal'; // <-- Impor komponen baru

export default function StartScreen({ onStart }) {
    const [showHowToPlayModal, setShowHowToPlayModal] = useState(false);
    const [showTrashTypeModal, setShowTrashTypeModal] = useState(false); // <-- State baru

    const handleShowHowToPlayModal = () => setShowHowToPlayModal(true);
    const handleCloseHowToPlayModal = () => setShowHowToPlayModal(false);

    const handleShowTrashTypeModal = () => setShowTrashTypeModal(true); // <-- Fungsi baru
    const handleCloseTrashTypeModal = () => setShowTrashTypeModal(false); // <-- Fungsi baru

    return (
        <div style={containerStyle}>
            <HeroSection
                onStart={onStart}
                onShowHowToPlay={handleShowHowToPlayModal}
                onShowTrashType={handleShowTrashTypeModal} // <-- Teruskan fungsi baru
            />
            <LeaderboardSection />
            <RewardSection />

            {showHowToPlayModal && <HowToPlayModal onClose={handleCloseHowToPlayModal} />}
            {showTrashTypeModal && <TrashTypeModal onClose={handleCloseTrashTypeModal} />} {/* <-- Tampilkan modal baru */}
        </div>
    );
}

const containerStyle = {
    fontFamily: "'Poppins', sans-serif",
    height: '100%',
    width: '100%',
    boxSizing: 'border-box',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
};
