import React, { useState, useEffect } from 'react';
import {
  DndContext,
  useDraggable,
  useDroppable,
  TouchSensor,
  PointerSensor,
  useSensor,
  useSensors,
} from '@dnd-kit/core';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faLeaf, faRecycle, faBiohazard, faTimes, faClock, faStar } from '@fortawesome/free-solid-svg-icons';
import CancelGameModal from './CancelGameModal';

// --- DATA GAME & LEVEL ---
const additionalTrashItemsLvl2 = [
  { id: 'item-9', name: 'Plastik Makanan', type: 'Anorganik', img: 'https://assets-a1.kompasiana.com/items/album/2024/09/17/bahan-tas-daur-ulang-66e94718ed64150b976ed172.png' },
  { id: 'item-10', name: 'Ranting Pohon', type: 'Organik', img: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRYAG31MGqMohcla3qOW4JXimbsVZ_EQ5AzRw&s' },
  { id: 'item-11', name: 'Kaleng Cat', type: 'B3', img: 'https://media.istockphoto.com/id/1433619092/id/foto/kaleng-cat-bekas.jpg?s=612x612&w=0&k=20&c=V0L4hy9cr_WLutoCIV3Ck1a7c0LBCM-0gz0EMyqOKtA=' },
  { id: 'item-12', name: 'Kemasan Kaca', type: 'Anorganik', img: 'https://w7.pngwing.com/pngs/89/375/png-transparent-glass-bottle-fizzy-drinks-plastic-bottle-drink-glass-plastic-bottle-soft-drink.png' },
  { id: 'item-13', name: 'Ampas Kopi', type: 'Organik', img: 'https://www.bitkaorigin.com/assets/myback/js/elFinder/files/bitka-aneka-manfaat-ampas-kopi-header.jpg' },
];
const additionalTrashItemsLvl3 = [
  { id: 'item-14', name: 'Puntung Rokok', type: 'B3', img: 'https://citarumharum.jabarprov.go.id/eusina/uploads/2021/11/201603171342531_b.jpg' },
  { id: 'item-15', name: 'Kemasan Styrofoam', type: 'Anorganik', img: 'https://img.inews.co.id/media/1200/files/inews_new/2018/05/02/stor1.jpg' },
  { id: 'item-16', name: 'Sisa Nasi', type: 'Organik', img: 'https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/68/2025/02/11/IMG_6917-706007545.jpg' },
  { id: 'item-17', name: 'Cairan Pembersih', type: 'B3', img: 'https://siopen.balangankab.go.id/storage/merchant/products/2024/03/16/f3e4ef1a1be994fecf66f122f409f6b5.jpg' },
  { id: 'item-18', name: 'Plastik Kresek', type: 'Anorganik', img: 'https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,f_auto,q_auto:best,w_640/v1513498394/ttobcfc3mgmpord9inan.jpg' },
  { id: 'item-19', name: 'Daun Kering', type: 'Organik', img: 'https://blog.kliknclean.com/wp-content/uploads/post_image/6%20Kegunaan%20Daun%20Kering_files/image001.jpg' },
  { id: 'item-20', name: 'Kaleng Semprot', type: 'B3', img: 'https://risetcdn.jatimtimes.com/images/2019/09/07/Kaleng-kaleng-mengandung-gas-mudah-terbakar-istaa451317e8fc1a35.md.jpg?quality=50' },
];
const initialTrashItems = {
  unclassified: [
    { id: 'item-1', name: 'Botol Plastik', type: 'Anorganik', img: 'https://cdn.pixabay.com/photo/2020/05/04/10/31/the-bottle-5128607_1280.jpg' },
    { id: 'item-2', name: 'Sisa Sayuran', type: 'Organik', img: 'https://asset.kompas.com/crops/0klZOkbAiD8vBkXLOh0inHHWe6k=/20x0:961x627/750x500/data/photo/2020/09/29/5f72f11b0b5ab.jpg' },
    { id: 'item-3', name: 'Baterai Bekas', type: 'B3', img: 'https://www.fobuma.com/mini/blog/73/73-2_ti.jpg?v=1611171161' },
    { id: 'item-4', name: 'Kertas Bekas', type: 'Anorganik', img: 'https://awsimages.detik.net.id/community/media/visual/2022/10/14/limbah-kertas-daur-ulang_169.jpeg?w=650' },
    { id: 'item-5', name: 'Lampu Neon', type: 'B3', img: 'https://mtlb.co.id/wp-content/uploads/2025/06/Picture1.jpg' },
    { id: 'item-6', name: 'Kulit Buah', type: 'Organik', img: 'https://www.riaumandiri.co/assets/berita/original/24496060889-foto_bermanfaat,_google.png' },
    { id: 'item-7', name: 'Kardus', type: 'Anorganik', img: 'https://c.files.bbci.co.uk/35D1/production/_113877731_board-1.jpg' },
    { id: 'item-8', name: 'Kaleng Minuman', type: 'Anorganik', img: 'https://media.istockphoto.com/id/458674645/id/foto/mendaur-ulang-kaleng-bekas.jpg?s=612x612&w=0&k=20&c=bFBfuXpWf2A6f_1AUQdAwm_sxMqDbJ9wnhIWLI7Xs6M=' },
  ],
  Organik: [],
  Anorganik: [],
  B3: [],
};
const levelData = {
  1: { duration: 15, items: initialTrashItems.unclassified },
  2: { duration: 90, items: [...initialTrashItems.unclassified, ...additionalTrashItemsLvl2] },
  3: { duration: 60, items: [...initialTrashItems.unclassified, ...additionalTrashItemsLvl2, ...additionalTrashItemsLvl3] },
};

// --- KOMPONEN ANAK ---
function ItemCard({ item, isSelected, onClick, isMobile }) {
  const { attributes, listeners, setNodeRef, transform, isDragging } = useDraggable({
    id: item.id,
    data: item,
    disabled: isMobile,
  });

  const style = {
    transform: transform ? `translate3d(${transform.x}px, ${transform.y}px, 0)` : undefined,
    zIndex: isDragging ? 100 : isSelected ? 90 : 'auto',
    opacity: isDragging ? 0.7 : 1,
    ...itemStyle,
    border: isSelected ? '3px solid #3b82f6' : '1px solid #e5e7eb',
    cursor: isMobile ? 'pointer' : 'grab',
  };

  const props = isMobile ? { onClick } : { onClick, ...listeners, ...attributes };

  return (
    <div ref={setNodeRef} style={style} {...props}>
      <div style={itemImageContainerStyle}>
        <img src={item.img} alt={item.name} style={{ width: '60px', height: '60px', objectFit: 'contain' }} />
      </div>
      <p style={itemLabelStyle}>{item.name}</p>
    </div>
  );
}

function TrashBin({ id, items, icon, color, onClick, isMobile, hasSelectedItem }) {
  const { setNodeRef, isOver } = useDroppable({ id });
  const isActiveTarget = isMobile && hasSelectedItem;

  const binDynamicStyle = {
    ...binStyle,
    borderColor: isOver || isActiveTarget ? color : '#e5e7eb',
    boxShadow: isOver || isActiveTarget ? `0 4px 14px 0 ${color}40` : '0 1px 2px 0 rgb(0 0 0 / 0.05)',
    cursor: isMobile && hasSelectedItem ? 'pointer' : 'default',
  };

  return (
    <div style={binContainerStyle}>
      <div style={binHeaderContainerStyle}>
        <div style={binHeaderStyle}>
          <FontAwesomeIcon icon={icon} style={{ color, fontSize: '20px' }} />
          <h3 style={binTitleStyle}>{id}</h3>
        </div>
        <div style={{ ...binHeaderDividerStyle, backgroundColor: color }} />
      </div>
      <div ref={setNodeRef} style={binDynamicStyle} onClick={onClick}>
        {items.length === 0 && (
          <p style={{ color: '#9ca3af', fontSize: '12px', textAlign: 'center' }}>
            {isMobile ? 'Klik di sini' : 'Letakkan di sini'}
          </p>
        )}
        {items.map((item) => (
          <DroppedItemCard key={item.id} item={item} />
        ))}
      </div>
    </div>
  );
}

function DroppedItemCard({ item }) {
  return (
    <div style={droppedItemStyle}>
      <img src={item.img} alt={item.name} style={{ width: '28px', height: '28px', objectFit: 'contain' }} />
      <span
        style={{
          fontSize: '11px',
          fontWeight: '500',
          color: '#4b5563',
          whiteSpace: 'nowrap',
          overflow: 'hidden',
          textOverflow: 'ellipsis',
        }}
      >
        {item.name}
      </span>
    </div>
  );
}

// --- GAME UTAMA ---
export default function PilahSampahGame({ onGameEnd = () => {} }) {
  const [currentLevel, setCurrentLevel] = useState(1);
  const [score, setScore] = useState(0);
  const [isGameActive, setIsGameActive] = useState(true);
  const [message, setMessage] = useState('');
  const [isGameOver, setIsGameOver] = useState(false);
  const [unclassifiedItems, setUnclassifiedItems] = useState([]);
  const [levelScores, setLevelScores] = useState({});
  const [showCancelModal, setShowCancelModal] = useState(false);
  const [timeLeft, setTimeLeft] = useState(90);
  const [trashItems, setTrashItems] = useState({ Organik: [], Anorganik: [], B3: [] });

  const [isMobile, setIsMobile] = useState(false);
  const [selectedItemId, setSelectedItemId] = useState(null);

  // Tambahan: status submit & CSRF
  const [isSubmitting, setIsSubmitting] = useState(false);
    // ganti konstanta csrf kamu dengan fungsi ini
    const getCsrf = () => {
    // 1) coba dari meta
    const meta = typeof document !== 'undefined'
        ? document.querySelector('meta[name="csrf-token"]')
        : null;
    if (meta?.content) return meta.content;

    // 2) fallback dari cookie XSRF-TOKEN (Laravel + Laravel Sanctum)
    if (typeof document !== 'undefined') {
        const m = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        if (m) return decodeURIComponent(m[1]);
    }
    return null;
    };


  const totalScore = Object.values(levelScores).reduce((sum, s) => sum + s, 0);

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(TouchSensor, { activationConstraint: { delay: 250, tolerance: 5 } })
  );

  useEffect(() => {
    const checkIsMobile = () => setIsMobile(window.innerWidth < 1024);
    checkIsMobile();
    window.addEventListener('resize', checkIsMobile);
    return () => window.removeEventListener('resize', checkIsMobile);
  }, []);

  useEffect(() => {
    const currentLevelData = levelData[currentLevel];
    if (currentLevelData) {
      setUnclassifiedItems([...currentLevelData.items]);
      setTrashItems({ Organik: [], Anorganik: [], B3: [] });
      setTimeLeft(currentLevelData.duration);
      setIsGameActive(true);
      setMessage('');
      setIsGameOver(false);
      setSelectedItemId(null);
    }
  }, [currentLevel]);

  useEffect(() => {
    if (!isGameActive || isGameOver || showCancelModal) return;
    if (timeLeft <= 0) {
      setIsGameActive(false);
      setMessage(`Waktu habis! Anda gagal menyelesaikan level ${currentLevel}.`);
      setIsGameOver(true);
      return;
    }
    const timer = setTimeout(() => setTimeLeft((t) => t - 1), 1000);
    return () => clearTimeout(timer);
  }, [timeLeft, isGameActive, isGameOver, showCancelModal, currentLevel]);

  const processItemDrop = (item, binId) => {
    if (!item) return;
    const isCorrect = item.type === binId;
    const newScore = score + (isCorrect ? 10 : -5);
    setScore(newScore);

    setUnclassifiedItems((prev) => prev.filter((i) => i.id !== item.id));
    setTrashItems((prev) => ({ ...prev, [binId]: [...prev[binId], item] }));

    if (unclassifiedItems.length === 1) {
      setIsGameActive(false);
      setLevelScores((prev) => ({ ...prev, [currentLevel]: newScore }));
      if (currentLevel < 3) {
        setMessage(`Level ${currentLevel} Selesai!`);
      } else {
        setMessage(`Selamat! Anda telah menyelesaikan semua level.`);
        setIsGameOver(true);
      }
    }
  };

  const handleDragEnd = (event) => {
    if (isMobile || !isGameActive) return;
    const { over, active } = event;
    if (over) {
      processItemDrop(active.data.current, over.id);
    }
  };

  const handleItemClick = (itemId) => {
    if (!isMobile || !isGameActive) return;
    setSelectedItemId((prevId) => (prevId === itemId ? null : itemId));
  };

  const handleBinClick = (binId) => {
    if (!isMobile || !isGameActive || !selectedItemId) return;
    const itemToMove = unclassifiedItems.find((it) => it.id === selectedItemId);
    if (itemToMove) {
      processItemDrop(itemToMove, binId);
      setSelectedItemId(null);
    }
  };

  const handleNextLevel = () => {
    if (currentLevel < 3) setCurrentLevel((prev) => prev + 1);
    else setIsGameOver(true);
  };

  // Total final ditampilkan saat popup akhir
  const finalTotalPoints = totalScore + (levelScores[currentLevel] !== undefined ? 0 : score);
const saveScore = async (finalTotalPoints) => {
  try {
    const token = getCsrf(); // ✅ ambil token pakai fungsi helper
    if (!token) {
      console.error("CSRF token tidak ditemukan");
      return;
    }

    const res = await fetch('/api/game/points', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,   // ✅ gunakan token
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      credentials: 'same-origin',
      body: JSON.stringify({ points: finalTotalPoints }),
    });

    if (!res.ok) {
      const errorData = await res.json();
      console.error("Gagal menyimpan skor:", errorData);
      setMessage(`Gagal menyimpan skor: ${errorData.message || 'Terjadi kesalahan.'}`);
    } else {
      const data = await res.json();
      console.log("Skor berhasil disimpan:", data);
      setMessage(`Skor berhasil disimpan! Total poin Anda: ${data.total_points}`);
    }
  } catch (error) {
    console.error("Terjadi kesalahan jaringan atau lainnya:", error);
    setMessage(`Terjadi kesalahan: ${error.message}`);
  } finally {
    setIsSubmitting(false);
  }
};

  const handleEndGameAndSaveScore = () => {
    setIsSubmitting(true);
    saveScore(finalTotalPoints); // Pass the final score to saveScore
    onGameEnd();
  };

  const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
  };

  return (
    <DndContext onDragEnd={handleDragEnd} sensors={sensors}>
      <div style={gameContainerStyle}>
        {(!isGameActive && (unclassifiedItems.length === 0 || isGameOver)) && (
          <div style={popupOverlayStyle}>
            <div style={popupModalStyle}>
              <h2 style={{ color: isGameOver ? '#dc2626' : '#16a34a' }}>
                {isGameOver ? 'Permainan Selesai!' : 'Level Selesai!'}
              </h2>
              <p>{message}</p>
              <div style={scoreDetailsStyle}>
                <p>
                  Skor Level {currentLevel}: <b>{levelScores[currentLevel] || score} poin</b>
                </p>
                <p>
                  Total Poin Anda: <b>{finalTotalPoints} poin</b>
                </p>
              </div>
              {currentLevel < 3 && !isGameOver ? (
                <button style={nextLevelButtonStyle} onClick={handleNextLevel}>
                  Lanjut ke Level {currentLevel + 1}
                </button>
              ) : (
                <button
                  style={exitGameButtonStyle}
                  onClick={handleEndGameAndSaveScore}
                  disabled={isSubmitting}
                >
                  {isSubmitting ? 'Menyimpan...' : 'Selesai'}
                </button>
              )}
            </div>
          </div>
        )}

        {showCancelModal && (
          <CancelGameModal
            onCancel={handleEndGameAndSaveScore}
            onContinue={() => setShowCancelModal(false)}
          />
        )}

        <div style={headerStyle}>
          <div style={levelBoxStyle}>
            <span>LEVEL {currentLevel}</span>
          </div>
          <div style={infoBoxStyle}>
            <FontAwesomeIcon icon={faClock} style={{ color: '#16a34a' }} />
            <span>{formatTime(timeLeft)}</span>
          </div>
          <div style={infoBoxStyle}>
            <FontAwesomeIcon icon={faStar} style={{ color: '#facc15' }} />
            <span>{score} point</span>
          </div>
          <button style={exitButtonStyle} onClick={() => setShowCancelModal(true)}>
            <FontAwesomeIcon icon={faTimes} />
          </button>
        </div>

        <div style={binsContainerStyle}>
          <TrashBin
            id="Organik"
            items={trashItems.Organik}
            icon={faLeaf}
            color="#22c55e"
            onClick={() => handleBinClick('Organik')}
            isMobile={isMobile}
            hasSelectedItem={!!selectedItemId}
          />
          <TrashBin
            id="Anorganik"
            items={trashItems.Anorganik}
            icon={faRecycle}
            color="#f59e0b"
            onClick={() => handleBinClick('Anorganik')}
            isMobile={isMobile}
            hasSelectedItem={!!selectedItemId}
          />
          <TrashBin
            id="B3"
            items={trashItems.B3}
            icon={faBiohazard}
            color="#ef4444"
            onClick={() => handleBinClick('B3')}
            isMobile={isMobile}
            hasSelectedItem={!!selectedItemId}
          />
        </div>

        <div style={itemsContainerStyle}>
          <h3 style={{ width: '100%', textAlign: 'center', color: '#444', marginBottom: '10px' }}>
            {isMobile ? 'Pilih Sampah Lalu Klik Tempat Sampah' : 'Pilih dan Geser Sampah'}
          </h3>
          {unclassifiedItems.length > 0 ? (
            unclassifiedItems.map((item) => (
              <ItemCard
                key={item.id}
                item={item}
                isSelected={item.id === selectedItemId}
                onClick={() => handleItemClick(item.id)}
                isMobile={isMobile}
              />
            ))
          ) : (
            <div style={messageBoxStyle}>
              <p style={{ fontSize: '18px', fontWeight: '500', color: '#22c55e' }}>
                🎉 Semua sampah telah diklasifikasikan! 🎉
              </p>
            </div>
          )}
        </div>
      </div>
    </DndContext>
  );
}

// --- STYLING ---
const gameContainerStyle = {
  background: 'white',
  padding: '24px',
  borderRadius: '24px',
  fontFamily: "'Poppins', sans-serif",
  position: 'relative',
  height: '100%',
  boxSizing: 'border-box',
  display: 'flex',
  flexDirection: 'column',
  boxShadow: '0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
};
const popupOverlayStyle = {
  position: 'absolute',
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: 'rgba(17, 24, 39, 0.6)',
  backdropFilter: 'blur(4px)',
  WebkitBackdropFilter: 'blur(4px)',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  zIndex: 300,
};
const popupModalStyle = {
  background: 'white',
  padding: '32px',
  borderRadius: '16px',
  textAlign: 'center',
  boxShadow: '0 25px 50px -12px rgb(0 0 0 / 0.25)',
  width: '400px',
  maxWidth: '90%',
};
const scoreDetailsStyle = { marginTop: '20px', padding: '16px', background: '#f3f4f6', borderRadius: '10px' };
const nextLevelButtonStyle = {
  marginTop: '20px',
  padding: '12px 24px',
  fontSize: '16px',
  fontWeight: '600',
  color: 'white',
  background: '#10b981',
  border: 'none',
  borderRadius: '8px',
  cursor: 'pointer',
};
const exitGameButtonStyle = {
  marginTop: '20px',
  padding: '12px 24px',
  fontSize: '16px',
  fontWeight: '600',
  color: 'white',
  background: '#dc2626',
  border: 'none',
  borderRadius: '8px',
  cursor: 'pointer',
};
const headerStyle = { display: 'flex', alignItems: 'center', gap: '16px', marginBottom: '20px', flexWrap: 'wrap' };
const infoBoxStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  background: 'white',
  color: '#1f2937',
  padding: '8px 16px',
  borderRadius: '12px',
  fontSize: '18px',
  fontWeight: '600',
  boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
};
const levelBoxStyle = { ...infoBoxStyle, background: '#dcfce7', color: '#16a34a' };
const exitButtonStyle = {
  marginLeft: 'auto',
  background: '#fee2e2',
  color: '#ef4444',
  border: 'none',
  width: '40px',
  height: '40px',
  borderRadius: '50%',
  cursor: 'pointer',
  fontSize: '18px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  transition: 'background-color 0.2s ease',
  boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
};
const binsContainerStyle = { display: 'flex', justifyContent: 'space-around', gap: '20px', marginBottom: '20px', flex: 1 };
const binContainerStyle = { flex: 1, background: 'white', borderRadius: '16px', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)', display: 'flex', flexDirection: 'column' };
const binHeaderContainerStyle = { display: 'flex', flexDirection: 'column', alignItems: 'center', padding: '12px 12px 0 12px' };
const binHeaderStyle = { display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '10px', background: 'transparent', width: '100%' };
const binHeaderDividerStyle = { height: '3px', width: '100%', borderRadius: '4px', marginTop: '10px', marginBottom: '10px' };
const binTitleStyle = { margin: 0, color: '#111827', fontWeight: '600', fontSize: '16px' };
const binStyle = { flex: 1, margin: '0 12px 12px 12px', background: '#f9fafb', borderRadius: '8px', padding: '12px', minHeight: '200px', border: '2px dashed #e5e7eb', display: 'flex', flexDirection: 'column', gap: '8px', alignItems: 'center', justifyContent: 'flex-start', transition: 'all 0.2s ease' };
const itemsContainerStyle = { flexGrow: 1, display: 'flex', flexWrap: 'wrap', justifyContent: 'center', alignItems: 'flex-start', gap: '16px', background: 'rgba(255,255,255,0.8)', padding: '20px', borderRadius: '16px' };
const itemStyle = { width: '100px', height: '110px', background: 'white', borderRadius: '12px', border: '1px solid #e5e7eb', boxShadow: '0 1px 3px rgba(0,0,0,0.1)', transition: 'all 0.2s ease', userSelect: 'none', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '8px' };
const itemImageContainerStyle = { marginBottom: '8px', display: 'flex', justifyContent: 'center', alignItems: 'center', height: '60px' };
const itemLabelStyle = { margin: '0', fontSize: '12px', fontWeight: '600', textAlign: 'center', color: '#1f2937' };
const droppedItemStyle = { width: '90%', display: 'flex', alignItems: 'center', gap: '8px', background: '#f3f4f6', borderRadius: '6px', padding: '6px', border: '1px solid #e5e7eb', boxShadow: '0 1px 2px rgba(0,0,0,0.05)' };
const messageBoxStyle = { width: '100%', textAlign: 'center', padding: '20px', background: '#dcfce7', borderRadius: '12px' };
