import { jsx, jsxs, Fragment } from "react/jsx-runtime";
import axios from "axios";
import Alpine from "alpinejs";
import React, { useState, useEffect } from "react";
import { createRoot } from "react-dom/client";
import { DndContext, useDroppable, useDraggable } from "@dnd-kit/core";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faClock, faStar, faTimes, faLeaf, faRecycle, faBiohazard, faInfoCircle, faPlay, faTrophy, faUser, faGift } from "@fortawesome/free-solid-svg-icons";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
function CancelGameModal({ onCancel, onContinue }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle$2, children: /* @__PURE__ */ jsxs("div", { style: modalStyle$2, children: [
    /* @__PURE__ */ jsx("h2", { style: titleStyle$2, children: "Batalkan Permainan?" }),
    /* @__PURE__ */ jsxs("p", { style: messageStyle, children: [
      "Apakah kamu yakin ingin membatalkan permainan?",
      /* @__PURE__ */ jsx("br", {}),
      "Progresmu tidak akan disimpan."
    ] }),
    /* @__PURE__ */ jsxs("div", { style: buttonContainerStyle$1, children: [
      /* @__PURE__ */ jsx("button", { style: cancelButtonStyle, onClick: onCancel, children: "Ya, Batalkan" }),
      /* @__PURE__ */ jsx("button", { style: continueButtonStyle, onClick: onContinue, children: "Lanjutkan Bermain" })
    ] })
  ] }) });
}
const overlayStyle$2 = {
  position: "fixed",
  // <-- PERBAIKAN: Mengubah dari 'absolute' menjadi 'fixed'
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: "rgba(17, 24, 39, 0.6)",
  backdropFilter: "blur(4px)",
  WebkitBackdropFilter: "blur(4px)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1e3
};
const modalStyle$2 = {
  background: "white",
  padding: "40px",
  borderRadius: "20px",
  width: "550px",
  maxWidth: "90%",
  textAlign: "center",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const titleStyle$2 = {
  fontSize: "2em",
  fontWeight: "bold",
  marginBottom: "10px",
  color: "#1f2937"
};
const messageStyle = {
  fontSize: "0.9em",
  fontWeight: "300",
  marginBottom: "30px",
  color: "#4b5563",
  lineHeight: "1.5"
};
const buttonContainerStyle$1 = {
  display: "flex",
  gap: "20px",
  justifyContent: "center"
};
const baseButtonStyle$1 = {
  padding: "12px 28px",
  borderRadius: "10px",
  fontSize: "0.95em",
  fontWeight: "bold",
  cursor: "pointer",
  border: "none",
  transition: "transform 0.2s ease, box-shadow 0.2s ease"
};
const cancelButtonStyle = {
  ...baseButtonStyle$1,
  background: "#ef4444",
  color: "white"
};
const continueButtonStyle = {
  ...baseButtonStyle$1,
  background: "#22c55e",
  color: "white"
};
const additionalTrashItemsLvl2 = [
  { id: "item-9", name: "Plastik Makanan", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/1899/1899757.png" },
  { id: "item-10", name: "Ranting Pohon", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/2927/2927909.png" },
  { id: "item-11", name: "Kaleng Cat", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/3233/3233959.png" },
  { id: "item-12", name: "Kemasan Kaca", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/280/280053.png" },
  { id: "item-13", name: "Ampas Kopi", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/820/820251.png" }
];
const additionalTrashItemsLvl3 = [
  { id: "item-14", name: "Puntung Rokok", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/4844/4844781.png" },
  { id: "item-15", name: "Kemasan Styrofoam", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/8661/8661706.png" },
  { id: "item-16", name: "Sisa Nasi", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/3218/3218314.png" },
  { id: "item-17", name: "Cairan Pembersih", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/845/845579.png" },
  { id: "item-18", name: "Plastik Kresek", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/1429/1429074.png" },
  { id: "item-19", name: "Daun Kering", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/4117/4117621.png" },
  { id: "item-20", name: "Kaleng Semprot", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/121/121588.png" }
];
const initialTrashItems = {
  unclassified: [
    { id: "item-1", name: "Botol Plastik", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/179/179354.png" },
    { id: "item-2", name: "Sisa Sayuran", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/125/125807.png" },
    { id: "item-3", name: "Baterai Bekas", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/1498/1498565.png" },
    { id: "item-4", name: "Kertas Bekas", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/861/861214.png" },
    { id: "item-5", name: "Lampu Neon", type: "B3", img: "https://cdn-icons-png.flaticon.com/512/2874/2874130.png" },
    { id: "item-6", name: "Kulit Buah", type: "Organik", img: "https://cdn-icons-png.flaticon.com/512/887/887964.png" },
    { id: "item-7", name: "Kardus", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/280/280047.png" },
    { id: "item-8", name: "Kaleng Minuman", type: "Anorganik", img: "https://cdn-icons-png.flaticon.com/512/2764/2764835.png" }
  ]
};
const levelData = {
  1: { duration: 10, items: initialTrashItems.unclassified },
  2: { duration: 90, items: [...initialTrashItems.unclassified, ...additionalTrashItemsLvl2] },
  3: { duration: 60, items: [...initialTrashItems.unclassified, ...additionalTrashItemsLvl2, ...additionalTrashItemsLvl3] }
};
function DroppedItemCard({ item }) {
  return /* @__PURE__ */ jsxs("div", { style: droppedItemStyle, children: [
    /* @__PURE__ */ jsx("img", { src: item.img, alt: item.name, style: { width: "28px", height: "28px", objectFit: "contain" } }),
    /* @__PURE__ */ jsx("span", { style: { fontSize: "11px", fontWeight: "500", color: "#4b5563", whiteSpace: "nowrap", overflow: "hidden", textOverflow: "ellipsis" }, children: item.name })
  ] });
}
function DraggableItem({ item }) {
  const { attributes, listeners, setNodeRef, transform, isDragging } = useDraggable({ id: item.id, data: item });
  const style = {
    transform: transform ? `translate3d(${transform.x}px, ${transform.y}px, 0)` : void 0,
    zIndex: isDragging ? 100 : "auto",
    opacity: isDragging ? 0.7 : 1,
    ...itemStyle
  };
  return /* @__PURE__ */ jsxs("div", { ref: setNodeRef, style, ...listeners, ...attributes, children: [
    /* @__PURE__ */ jsx("div", { style: itemImageContainerStyle, children: /* @__PURE__ */ jsx("img", { src: item.img, alt: item.name, style: { width: "60px", height: "60px", objectFit: "contain", pointerEvents: "none" } }) }),
    /* @__PURE__ */ jsx("p", { style: itemLabelStyle, children: item.name })
  ] });
}
function DroppableBin({ id, items, icon, color }) {
  const { setNodeRef, isOver } = useDroppable({ id });
  const binDynamicStyle = {
    ...binStyle,
    borderColor: isOver ? color : "#e5e7eb",
    boxShadow: isOver ? `0 4px 14px 0 ${color}40` : "0 1px 2px 0 rgb(0 0 0 / 0.05)"
  };
  return /* @__PURE__ */ jsxs("div", { style: binContainerStyle, children: [
    /* @__PURE__ */ jsxs("div", { style: binHeaderContainerStyle, children: [
      /* @__PURE__ */ jsxs("div", { style: binHeaderStyle, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon, style: { color, fontSize: "20px" } }),
        /* @__PURE__ */ jsx("h3", { style: binTitleStyle, children: id })
      ] }),
      /* @__PURE__ */ jsx("div", { style: { ...binHeaderDividerStyle, backgroundColor: color } })
    ] }),
    /* @__PURE__ */ jsxs("div", { ref: setNodeRef, style: binDynamicStyle, children: [
      items.length === 0 && /* @__PURE__ */ jsx("p", { style: { color: "#9ca3af", fontSize: "12px", textAlign: "center" }, children: "Letakkan sampah di sini" }),
      items.map((item) => /* @__PURE__ */ jsx(DroppedItemCard, { item }, item.id))
    ] })
  ] });
}
function PilahSampahGame({ onGameEnd }) {
  const [currentLevel, setCurrentLevel] = useState(1);
  const [score, setScore] = useState(0);
  const [isGameActive, setIsGameActive] = useState(true);
  const [message, setMessage] = useState("");
  const [isGameOver, setIsGameOver] = useState(false);
  const [unclassifiedItems, setUnclassifiedItems] = useState([]);
  const [levelScores, setLevelScores] = useState({});
  const [showCancelModal, setShowCancelModal] = useState(false);
  const totalScore = Object.values(levelScores).reduce((sum, score2) => sum + score2, 0);
  useEffect(() => {
    const initialItems = [...levelData[currentLevel].items];
    setUnclassifiedItems(initialItems);
    setTrashItems({
      Organik: [],
      Anorganik: [],
      B3: []
    });
    setTimeLeft(levelData[currentLevel].duration);
    setIsGameActive(true);
    setMessage("");
    setIsGameOver(false);
  }, [currentLevel]);
  const [timeLeft, setTimeLeft] = useState(levelData[currentLevel].duration);
  const [trashItems, setTrashItems] = useState({
    Organik: [],
    Anorganik: [],
    B3: []
  });
  useEffect(() => {
    if (!isGameActive || isGameOver || showCancelModal) return;
    if (timeLeft <= 0) {
      setIsGameActive(false);
      if (unclassifiedItems.length > 0) {
        setIsGameOver(true);
        setMessage(`Waktu habis! Anda gagal menyelesaikan level ${currentLevel}.`);
      } else {
        if (currentLevel < 3) {
          setMessage(`Level ${currentLevel} Selesai!`);
        } else {
          setMessage(`Selamat! Anda telah menyelesaikan semua level.`);
          setIsGameOver(true);
        }
      }
      return;
    }
    const timer = setTimeout(() => setTimeLeft(timeLeft - 1), 1e3);
    return () => clearTimeout(timer);
  }, [timeLeft, isGameActive, isGameOver, currentLevel, unclassifiedItems.length, showCancelModal]);
  const handleNextLevel = () => {
    setIsGameActive(true);
    if (currentLevel < 3) {
      setCurrentLevel((prev) => prev + 1);
    } else {
      setIsGameOver(true);
    }
  };
  const handleEndGameAndSaveScore = async (finalScore) => {
    const userId = 1;
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute("content") : null;
    if (!csrfToken) {
      console.error("CSRF token tidak ditemukan.");
      onGameEnd();
      return;
    }
    try {
      console.log("Mencoba mengirim skor ke API...");
      const response = await fetch("/api/save-score", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
          user_id: userId,
          points: finalScore
        })
      });
      console.log("Respons server diterima:", response);
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Gagal menyimpan skor:", response.status, errorText);
        alert(`Gagal menyimpan skor. Server error: ${response.status} ${response.statusText}. Cek konsol untuk detail.`);
      } else {
        const data = await response.json();
        console.log("Skor berhasil disimpan:", data);
      }
    } catch (error) {
      console.error("Terjadi kesalahan saat menghubungi server:", error);
      alert("Terjadi kesalahan saat menghubungi server. Mohon periksa koneksi internet atau URL API.");
    } finally {
      onGameEnd();
    }
  };
  const handleDragEnd = (event) => {
    if (!isGameActive) return;
    const { over, active } = event;
    if (!over) return;
    const draggedItem = active.data.current;
    const targetBin = over.id;
    if (["Organik", "Anorganik", "B3"].includes(targetBin)) {
      let newScore = score;
      if (draggedItem.type === targetBin) {
        newScore += 10;
      } else {
        newScore -= 5;
      }
      setScore(newScore);
      const remainingItems = unclassifiedItems.filter((item) => item.id !== draggedItem.id);
      setUnclassifiedItems(remainingItems);
      setTrashItems((prev) => ({
        ...prev,
        [targetBin]: [...prev[targetBin], draggedItem]
      }));
      if (remainingItems.length === 0) {
        setIsGameActive(false);
        setLevelScores((prev) => ({ ...prev, [currentLevel]: newScore }));
        if (currentLevel < 3) {
          setMessage(`Level ${currentLevel} Selesai!`);
        } else {
          setMessage(`Selamat! Anda telah menyelesaikan semua level.`);
          setIsGameOver(true);
        }
      }
    }
  };
  const formatTime = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${minutes.toString().padStart(2, "0")}:${secs.toString().padStart(2, "0")}`;
  };
  return /* @__PURE__ */ jsx(DndContext, { onDragEnd: handleDragEnd, children: /* @__PURE__ */ jsxs("div", { style: gameContainerStyle, children: [
    !isGameActive && (unclassifiedItems.length === 0 || isGameOver) && /* @__PURE__ */ jsx("div", { style: popupOverlayStyle, children: /* @__PURE__ */ jsxs("div", { style: popupModalStyle, children: [
      /* @__PURE__ */ jsx("h2", { style: { color: isGameOver ? "#dc2626" : "#16a34a" }, children: isGameOver ? "Permainan Selesai!" : "Level Selesai!" }),
      /* @__PURE__ */ jsx("p", { children: message }),
      /* @__PURE__ */ jsxs("div", { style: scoreDetailsStyle, children: [
        /* @__PURE__ */ jsxs("p", { children: [
          "Skor Level ",
          currentLevel,
          ": ",
          /* @__PURE__ */ jsxs("b", { children: [
            score,
            " poin"
          ] })
        ] }),
        /* @__PURE__ */ jsxs("p", { children: [
          "Total Poin Anda: ",
          /* @__PURE__ */ jsxs("b", { children: [
            totalScore,
            " poin"
          ] })
        ] })
      ] }),
      currentLevel < 3 && !isGameOver ? /* @__PURE__ */ jsxs("button", { style: nextLevelButtonStyle, onClick: handleNextLevel, children: [
        "Lanjut ke Level ",
        currentLevel + 1
      ] }) : /* @__PURE__ */ jsx("button", { style: exitGameButtonStyle, onClick: () => handleEndGameAndSaveScore(totalScore), children: "Selesai" })
    ] }) }),
    showCancelModal && /* @__PURE__ */ jsx(
      CancelGameModal,
      {
        onCancel: () => handleEndGameAndSaveScore(totalScore),
        onContinue: () => setShowCancelModal(false)
      }
    ),
    /* @__PURE__ */ jsxs("div", { style: headerStyle$1, children: [
      /* @__PURE__ */ jsx("div", { style: levelBoxStyle, children: /* @__PURE__ */ jsxs("span", { children: [
        "LEVEL ",
        currentLevel
      ] }) }),
      /* @__PURE__ */ jsxs("div", { style: infoBoxStyle, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faClock, style: { color: "#16a34a" } }),
        /* @__PURE__ */ jsx("span", { children: formatTime(timeLeft) })
      ] }),
      /* @__PURE__ */ jsxs("div", { style: infoBoxStyle, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faStar, style: { color: "#facc15" } }),
        /* @__PURE__ */ jsxs("span", { children: [
          score,
          " point"
        ] })
      ] }),
      /* @__PURE__ */ jsx("button", { style: exitButtonStyle, onClick: () => setShowCancelModal(true), children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) })
    ] }),
    /* @__PURE__ */ jsxs("div", { style: binsContainerStyle, children: [
      /* @__PURE__ */ jsx(DroppableBin, { id: "Organik", items: trashItems.Organik, icon: faLeaf, color: "#22c55e" }),
      /* @__PURE__ */ jsx(DroppableBin, { id: "Anorganik", items: trashItems.Anorganik, icon: faRecycle, color: "#f59e0b" }),
      /* @__PURE__ */ jsx(DroppableBin, { id: "B3", items: trashItems.B3, icon: faBiohazard, color: "#ef4444" })
    ] }),
    /* @__PURE__ */ jsxs("div", { style: itemsContainerStyle, children: [
      /* @__PURE__ */ jsx("h3", { style: { width: "100%", textAlign: "center", color: "#444", marginBottom: "10px" }, children: "Pilih dan Geser Sampah di Bawah Ini" }),
      unclassifiedItems.length > 0 ? unclassifiedItems.map((item) => /* @__PURE__ */ jsx(DraggableItem, { item }, item.id)) : /* @__PURE__ */ jsx("div", { style: messageBoxStyle, children: /* @__PURE__ */ jsx("p", { style: { fontSize: "18px", fontWeight: "500", color: "#22c55e" }, children: "🎉 Semua sampah telah diklasifikasikan! 🎉" }) })
    ] })
  ] }) });
}
const gameContainerStyle = {
  background: "white",
  padding: "24px",
  borderRadius: "24px",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  height: "100%",
  boxSizing: "border-box",
  display: "flex",
  flexDirection: "column",
  boxShadow: "0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)"
};
const popupOverlayStyle = { position: "absolute", top: 0, left: 0, right: 0, bottom: 0, background: "rgba(17, 24, 39, 0.6)", backdropFilter: "blur(4px)", WebkitBackdropFilter: "blur(4px)", display: "flex", alignItems: "center", justifyContent: "center", zIndex: 300 };
const popupModalStyle = { background: "white", padding: "32px", borderRadius: "16px", textAlign: "center", boxShadow: "0 25px 50px -12px rgb(0 0 0 / 0.25)", width: "400px", maxWidth: "90%" };
const scoreDetailsStyle = { marginTop: "20px", padding: "16px", background: "#f3f4f6", borderRadius: "10px" };
const nextLevelButtonStyle = { marginTop: "20px", padding: "12px 24px", fontSize: "16px", fontWeight: "600", color: "white", background: "#10b981", border: "none", borderRadius: "8px", cursor: "pointer" };
const exitGameButtonStyle = { marginTop: "20px", padding: "12px 24px", fontSize: "16px", fontWeight: "600", color: "white", background: "#dc2626", border: "none", borderRadius: "8px", cursor: "pointer" };
const headerStyle$1 = { display: "flex", alignItems: "center", gap: "16px", marginBottom: "20px" };
const infoBoxStyle = { display: "flex", alignItems: "center", gap: "8px", background: "white", color: "#1f2937", padding: "8px 16px", borderRadius: "12px", fontSize: "18px", fontWeight: "600", boxShadow: "0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)" };
const levelBoxStyle = { ...infoBoxStyle, background: "#dcfce7", color: "#16a34a" };
const exitButtonStyle = { marginLeft: "auto", background: "#fee2e2", color: "#ef4444", border: "none", width: "40px", height: "40px", borderRadius: "50%", cursor: "pointer", fontSize: "18px", display: "flex", alignItems: "center", justifyContent: "center", transition: "background-color 0.2s ease", boxShadow: "0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)" };
const binsContainerStyle = { display: "flex", justifyContent: "space-around", gap: "20px", marginBottom: "20px", flex: 1 };
const binContainerStyle = { flex: 1, background: "white", borderRadius: "16px", boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)" };
const binHeaderContainerStyle = {
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  padding: "12px 12px 0 12px"
};
const binHeaderStyle = {
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  gap: "10px",
  background: "transparent",
  width: "100%"
};
const binHeaderDividerStyle = {
  height: "3px",
  width: "100%",
  borderRadius: "4px",
  marginTop: "10px",
  marginBottom: "20px"
};
const binTitleStyle = { margin: 0, color: "#111827", fontWeight: "600", fontSize: "16px" };
const binStyle = { margin: "0 12px 12px 12px", background: "#f9fafb", borderRadius: "8px", padding: "12px", minHeight: "300px", border: "2px dashed #e5e7eb", display: "flex", flexDirection: "column", gap: "8px", alignItems: "center", justifyContent: "flex-start", transition: "all 0.2s ease" };
const itemsContainerStyle = { flexGrow: 1, display: "flex", flexWrap: "wrap", justifyContent: "center", alignItems: "flex-start", gap: "16px", background: "rgba(255,255,255,0.8)", padding: "20px", borderRadius: "16px" };
const itemStyle = {
  width: "100px",
  height: "110px",
  background: "white",
  borderRadius: "12px",
  cursor: "grab",
  border: "1px solid #e5e7eb",
  boxShadow: "0 1px 3px rgba(0,0,0,0.1)",
  transition: "all 0.2s ease",
  userSelect: "none",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  justifyContent: "center",
  padding: "8px"
};
const itemImageContainerStyle = {
  marginBottom: "8px",
  display: "flex",
  justifyContent: "center"
};
const itemLabelStyle = {
  margin: "0",
  fontSize: "12px",
  fontWeight: "600",
  textAlign: "center",
  color: "#1f2937"
};
const droppedItemStyle = { width: "90%", display: "flex", alignItems: "center", gap: "8px", background: "#f3f4f6", borderRadius: "6px", padding: "6px", border: "1px solid #e5e7eb", boxShadow: "0 1px 2px rgba(0,0,0,0.05)" };
const messageBoxStyle = { width: "100%", textAlign: "center", padding: "20px", background: "#dcfce7", borderRadius: "12px" };
function HeroSection({ onStart, onShowHowToPlay, onShowTrashType }) {
  return /* @__PURE__ */ jsxs("div", { style: heroContentStyle, children: [
    /* @__PURE__ */ jsx("div", { style: imageContainerStyle, children: /* @__PURE__ */ jsx("img", { src: "img/logo.png", alt: "Trash Can Robot", style: { height: "150px" } }) }),
    /* @__PURE__ */ jsx("h1", { style: gameTitleStyle, children: "Game Pilah Sampah" }),
    /* @__PURE__ */ jsxs("div", { style: buttonContainerStyle, children: [
      /* @__PURE__ */ jsxs("button", { style: secondaryButtonStyle, onClick: onShowHowToPlay, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faInfoCircle, style: { marginRight: "8px" } }),
        "Cara bermain"
      ] }),
      /* @__PURE__ */ jsxs("button", { style: primaryButtonStyle, onClick: onStart, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faPlay, style: { marginRight: "8px" } }),
        "MULAI"
      ] }),
      /* @__PURE__ */ jsxs("button", { style: secondaryButtonStyle, onClick: onShowTrashType, children: [
        " ",
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faRecycle, style: { marginRight: "8px" } }),
        "Jenis Sampah"
      ] })
    ] })
  ] });
}
const heroContentStyle = {
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  textAlign: "center",
  width: "100%",
  maxWidth: "960px",
  border: "1px solid rgba(255, 255, 255, 0.2)",
  backdropFilter: "blur(10px)",
  borderRadius: "20px",
  padding: "50px 70px",
  marginBottom: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  backgroundColor: "white"
};
const imageContainerStyle = {
  marginBottom: "20px"
};
const gameTitleStyle = {
  fontSize: "3em",
  fontWeight: "800",
  marginBottom: "30px",
  textShadow: "2px 2px 4px rgba(0,0,0,0.1)",
  color: "#16a34a"
};
const buttonContainerStyle = {
  display: "flex",
  gap: "20px",
  justifyContent: "center"
};
const baseButtonStyle = {
  padding: "12px 24px",
  borderRadius: "12px",
  fontSize: "1em",
  fontWeight: "bold",
  cursor: "pointer",
  border: "none",
  boxShadow: "0 4px 12px rgba(0,0,0,0.2)",
  transition: "transform 0.3s ease, box-shadow 0.3s ease",
  display: "flex",
  alignItems: "center",
  textTransform: "uppercase"
};
const primaryButtonStyle = {
  ...baseButtonStyle,
  background: "#10b981",
  color: "white",
  "&:hover": {
    transform: "scale(1.05)",
    boxShadow: "0 6px 16px rgba(0,0,0,0.3)"
  }
};
const secondaryButtonStyle = {
  ...baseButtonStyle,
  background: "white",
  color: "#1f2937",
  "&:hover": {
    transform: "scale(1.05)",
    boxShadow: "0 6px 16px rgba(0,0,0,0.3)"
  }
};
function LeaderboardSection() {
  const [leaderboardData, setLeaderboardData] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    const fetchLeaderboard = async () => {
      try {
        const response = await fetch("/api/leaderboard");
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`);
        }
        const data = await response.json();
        setLeaderboardData(data);
      } catch (error) {
        console.error("Error fetching leaderboard data:", error);
        setLeaderboardData([
          { username: "User 1", total_points: 1e3 },
          { username: "User 2", total_points: 900 },
          { username: "User 3", total_points: 800 },
          { username: "User 4", total_points: 750 },
          { username: "User 5", total_points: 700 },
          { username: "User 6", total_points: 650 },
          { username: "User 7", total_points: 600 },
          { username: "User 8", total_points: 550 },
          { username: "User 9", total_points: 500 },
          { username: "User 10", total_points: 450 }
        ]);
      } finally {
        setLoading(false);
      }
    };
    fetchLeaderboard();
  }, []);
  const rankedData = leaderboardData.map((item, index) => ({
    ...item,
    rank: index + 1
  }));
  const top3Rankings = rankedData.length > 0 ? [rankedData[1], rankedData[0], rankedData[2]] : [];
  const otherRanks = rankedData.slice(3);
  return /* @__PURE__ */ jsxs("div", { style: leaderboardSectionStyle$1, children: [
    /* @__PURE__ */ jsx("h2", { style: leaderboardTitleStyle, children: "Leaderboard" }),
    loading ? /* @__PURE__ */ jsx("p", { children: "Memuat Leaderboard..." }) : /* @__PURE__ */ jsxs(Fragment, { children: [
      /* @__PURE__ */ jsx("div", { style: top3ContainerStyle$1, children: top3Rankings.map((player) => /* @__PURE__ */ jsxs("div", { style: top3CardStyle$1(player.rank), children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTrophy, style: trophyStyle$1(player.rank) }),
        /* @__PURE__ */ jsx("h3", { style: rankNumberTop3Style$1, children: player.rank }),
        /* @__PURE__ */ jsx("div", { style: usernameIconContainerStyle$1, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faUser, style: usernameIconStyle$1 }) }),
        /* @__PURE__ */ jsx("p", { style: usernameTop3Style$1, children: player.username }),
        /* @__PURE__ */ jsxs("p", { style: scoreTop3Style$1, children: [
          player.total_points,
          " Points"
        ] })
      ] }, player.username)) }),
      /* @__PURE__ */ jsx("div", { style: otherRanksContainerStyle$1, children: otherRanks.map((player) => /* @__PURE__ */ jsxs("div", { style: rankItemStyle$1, children: [
        /* @__PURE__ */ jsx("span", { style: rankNumberStyle$1, children: player.rank }),
        /* @__PURE__ */ jsxs("div", { style: { display: "flex", alignItems: "center", gap: "10px", flex: 1 }, children: [
          /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faUser, style: rankIconStyle$1 }),
          /* @__PURE__ */ jsx("span", { style: usernameStyle$1, children: player.username })
        ] }),
        /* @__PURE__ */ jsxs("span", { style: scoreStyle$1, children: [
          /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faStar, style: { color: "#facc15", marginRight: "5px" } }),
          player.total_points,
          " Pts"
        ] })
      ] }, player.username)) })
    ] })
  ] });
}
const leaderboardSectionStyle$1 = {
  // Mengubah background menjadi putih solid
  backgroundColor: "white",
  width: "100%",
  maxWidth: "960px",
  borderRadius: "20px",
  padding: "50px 70px",
  marginBottom: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  textAlign: "center"
};
const leaderboardTitleStyle = {
  marginBottom: "40px",
  color: "#16a34a",
  fontSize: "2em",
  fontWeight: "800",
  textShadow: "2px 2px 4px rgba(0,0,0,0.1)"
};
const top3ContainerStyle$1 = {
  display: "flex",
  justifyContent: "center",
  alignItems: "flex-end",
  gap: "20px",
  marginBottom: "20px"
};
const top3CardStyle$1 = (rank) => ({
  width: "120px",
  padding: "16px 12px",
  background: rank === 1 ? "#b91c1c" : rank === 2 ? "#a16207" : "#57534e",
  color: "white",
  borderRadius: "12px",
  position: "relative",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  minHeight: rank === 1 ? "220px" : rank === 2 ? "200px" : "180px",
  justifyContent: "space-between",
  boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`,
  transition: "all 0.3s ease"
});
const rankNumberTop3Style$1 = {
  margin: "0",
  fontSize: "2.5em",
  fontWeight: "800"
};
const usernameIconContainerStyle$1 = {
  width: "50px",
  height: "50px",
  borderRadius: "50%",
  background: "rgba(255,255,255,0.2)",
  display: "flex",
  alignItems: "center",
  justifyContent: "center"
};
const usernameIconStyle$1 = {
  fontSize: "2em",
  color: "white"
};
const usernameTop3Style$1 = {
  margin: "0",
  fontWeight: "bold",
  fontSize: "1em"
};
const scoreTop3Style$1 = {
  margin: "4px 0 0",
  fontSize: "0.8em",
  color: "#facc15"
};
const trophyStyle$1 = (rank) => ({
  fontSize: "3em",
  color: rank === 1 ? "#facc15" : rank === 2 ? "#e5e7eb" : "#a16207",
  position: "absolute",
  top: "-20px",
  left: "50%",
  transform: "translateX(-50%)",
  filter: "drop-shadow(0 2px 2px rgba(0,0,0,0.2))"
});
const otherRanksContainerStyle$1 = {
  // Mengubah background menjadi putih solid
  backgroundColor: "white",
  padding: "10px",
  borderRadius: "12px",
  border: "1px solid rgba(0,0,0,0.1)"
};
const rankItemStyle$1 = {
  display: "flex",
  alignItems: "center",
  background: "rgba(243, 244, 246, 0.5)",
  // Warna latar belakang item
  borderRadius: "8px",
  padding: "10px 15px",
  justifyContent: "space-between",
  marginBottom: "5px"
};
const rankNumberStyle$1 = {
  fontWeight: "bold",
  color: "#1f2937",
  width: "20px",
  textAlign: "center"
};
const usernameStyle$1 = {
  flex: 1,
  textAlign: "left",
  color: "#1f2937",
  fontWeight: "bold"
};
const rankIconStyle$1 = {
  fontSize: "1em",
  color: "#1f2937"
};
const scoreStyle$1 = {
  fontWeight: "bold",
  color: "#facc15",
  fontSize: "0.9em"
};
function RewardSection() {
  const dummyRewards = [
    { id: 1, name: "Reward 1", points: 1e3 },
    { id: 2, name: "Reward 2", points: 2e3 },
    { id: 3, name: "Reward 3", points: 3e3 }
  ];
  return /* @__PURE__ */ jsxs("div", { style: rewardSectionStyle$1, children: [
    /* @__PURE__ */ jsx("h2", { style: { color: "#16a34a", fontSize: "2em", fontWeight: "800", marginBottom: "20px" }, children: "Klaim Reward" }),
    /* @__PURE__ */ jsx("div", { style: rewardBoxesContainerStyle$1, children: dummyRewards.map((reward) => /* @__PURE__ */ jsxs("div", { style: rewardBoxStyle$1, children: [
      /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faGift, style: giftIconStyle$1 }),
      /* @__PURE__ */ jsx("p", { style: { margin: "0", fontWeight: "bold" }, children: reward.name }),
      /* @__PURE__ */ jsxs("p", { style: { margin: "4px 0", fontSize: "0.9em", color: "#6b7280" }, children: [
        reward.points,
        " pts"
      ] }),
      /* @__PURE__ */ jsx("button", { style: claimButtonStyle$1, children: "Klaim" })
    ] }, reward.id)) })
  ] });
}
const rewardSectionStyle$1 = {
  // Menyamakan container dengan Hero dan Leaderboard
  width: "100%",
  maxWidth: "960px",
  backgroundColor: "white",
  borderRadius: "20px",
  padding: "50px 70px",
  marginBottom: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  textAlign: "center"
};
const rewardBoxesContainerStyle$1 = {
  display: "flex",
  justifyContent: "space-around",
  gap: "20px",
  marginTop: "20px"
};
const rewardBoxStyle$1 = {
  flex: 1,
  background: "transparent",
  borderRadius: "12px",
  padding: "20px",
  border: "1px solid #e5e7eb",
  textAlign: "center",
  boxShadow: "0 1px 3px rgba(0,0,0,0.1)"
};
const giftIconStyle$1 = {
  fontSize: "3em",
  color: "#10b981",
  // Mengubah warna ikon menjadi lebih gelap
  marginBottom: "10px"
};
const claimButtonStyle$1 = {
  marginTop: "10px",
  padding: "8px 16px",
  background: "#10b981",
  color: "white",
  border: "none",
  borderRadius: "6px",
  cursor: "pointer",
  fontWeight: "bold",
  transition: "background-color 0.3s ease",
  "&:hover": {
    backgroundColor: "#059669"
  }
};
function HowToPlayModal({ onClose }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle$1, children: /* @__PURE__ */ jsxs("div", { style: modalStyle$1, children: [
    /* @__PURE__ */ jsx("button", { style: closeButtonStyle$1, onClick: onClose, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) }),
    /* @__PURE__ */ jsx("h2", { style: titleStyle$1, children: "Cara Bermain" }),
    /* @__PURE__ */ jsx("p", { style: subtitleStyle$1, children: "Yuk, pelajari dulu cara bermainnya agar kamu siap jadi pahlawan lingkungan" }),
    /* @__PURE__ */ jsxs("ol", { style: listStyle$1, children: [
      /* @__PURE__ */ jsx("li", { children: "Seret (drag) atau klik sampah yang muncul di layar." }),
      /* @__PURE__ */ jsx("li", { children: "Arahkan ke tempat sampah yang sesuai dengan jenisnya." }),
      /* @__PURE__ */ jsx("li", { children: "Setiap sampah yang dimasukkan dengan benar akan menambah poin." }),
      /* @__PURE__ */ jsx("li", { children: "Hati-hati! Memasukkan ke tempat yang salah akan mengurangi poin." }),
      /* @__PURE__ */ jsx("li", { children: "Selesaikan semua sampah dalam waktu yang tersedia." })
    ] }),
    /* @__PURE__ */ jsx("button", { style: backButtonStyle$1, onClick: onClose, children: "Kembali" })
  ] }) });
}
const overlayStyle$1 = {
  position: "fixed",
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: "rgba(0, 0, 0, 0.6)",
  backdropFilter: "blur(5px)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1e3
};
const modalStyle$1 = {
  background: "white",
  // Latar belakang putih
  padding: "40px 60px",
  borderRadius: "20px",
  width: "450px",
  maxWidth: "90%",
  textAlign: "center",
  color: "#1f2937",
  // Warna teks gelap
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const closeButtonStyle$1 = {
  position: "absolute",
  top: "15px",
  right: "15px",
  background: "none",
  border: "none",
  color: "#4b5563",
  // Warna ikon gelap
  fontSize: "24px",
  cursor: "pointer"
};
const titleStyle$1 = {
  fontSize: "2.5em",
  fontWeight: "bold",
  marginBottom: "10px",
  color: "#16a34a",
  // Warna judul hijau
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const subtitleStyle$1 = {
  fontSize: "1em",
  fontWeight: "300",
  marginBottom: "20px",
  color: "#4b5563"
  // Warna teks abu-abu gelap
};
const listStyle$1 = {
  textAlign: "left",
  fontSize: "1em",
  lineHeight: "1.8",
  marginBottom: "30px",
  paddingLeft: "20px",
  color: "#1f2937",
  listStyleType: "decimal"
  // Penomoran
};
const backButtonStyle$1 = {
  background: "#10b981",
  // Latar belakang tombol hijau
  color: "white",
  padding: "12px 30px",
  border: "none",
  borderRadius: "10px",
  cursor: "pointer",
  fontSize: "1em",
  fontWeight: "bold",
  transition: "transform 0.2s ease",
  "&:hover": {
    transform: "scale(1.05)"
  }
};
function TrashTypeModal({ onClose }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle, children: /* @__PURE__ */ jsxs("div", { style: modalStyle, children: [
    /* @__PURE__ */ jsx("button", { style: closeButtonStyle, onClick: onClose, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) }),
    /* @__PURE__ */ jsx("h2", { style: titleStyle, children: "Jenis Sampah" }),
    /* @__PURE__ */ jsx("p", { style: subtitleStyle, children: "Memisahkan sampah ke dalam tiga jenis tempat sampah yang benar" }),
    /* @__PURE__ */ jsxs("ol", { style: listStyle, children: [
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsx("span", { style: { color: "#16a34a", fontWeight: "bold" }, children: "Organik" }),
        " – Sampah yang bisa terurai, seperti sisa makanan, daun, kulit buah."
      ] }),
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsx("span", { style: { color: "#f59e0b", fontWeight: "bold" }, children: "Anorganik" }),
        " – Sampah yang sulit terurai, seperti plastik, botol minum, kertas."
      ] }),
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsx("span", { style: { color: "#ef4444", fontWeight: "bold" }, children: "B3" }),
        " (Bahan Berbahaya & Beracun) – Sampah berbahaya seperti baterai, kaca pecah, obat kadaluarsa."
      ] })
    ] }),
    /* @__PURE__ */ jsx("button", { style: backButtonStyle, onClick: onClose, children: "Kembali" })
  ] }) });
}
const overlayStyle = {
  position: "fixed",
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: "rgba(0, 0, 0, 0.6)",
  backdropFilter: "blur(5px)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1e3
};
const modalStyle = {
  background: "white",
  padding: "40px 60px",
  borderRadius: "20px",
  width: "450px",
  maxWidth: "90%",
  textAlign: "center",
  color: "#1f2937",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const closeButtonStyle = {
  position: "absolute",
  top: "15px",
  right: "15px",
  background: "none",
  border: "none",
  color: "#4b5563",
  fontSize: "24px",
  cursor: "pointer"
};
const titleStyle = {
  fontSize: "2.5em",
  fontWeight: "bold",
  marginBottom: "10px",
  color: "#16a34a",
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const subtitleStyle = {
  fontSize: "1em",
  fontWeight: "300",
  marginBottom: "20px",
  color: "#4b5563"
};
const listStyle = {
  textAlign: "left",
  fontSize: "1em",
  lineHeight: "1.8",
  marginBottom: "30px",
  paddingLeft: "20px",
  color: "#1f2937",
  listStyleType: "decimal"
};
const backButtonStyle = {
  background: "#10b981",
  color: "white",
  padding: "12px 30px",
  border: "none",
  borderRadius: "10px",
  cursor: "pointer",
  fontSize: "1em",
  fontWeight: "bold",
  transition: "transform 0.2s ease",
  "&:hover": {
    transform: "scale(1.05)"
  }
};
function StartScreen({ onStart }) {
  const [showHowToPlayModal, setShowHowToPlayModal] = useState(false);
  const [showTrashTypeModal, setShowTrashTypeModal] = useState(false);
  const handleShowHowToPlayModal = () => setShowHowToPlayModal(true);
  const handleCloseHowToPlayModal = () => setShowHowToPlayModal(false);
  const handleShowTrashTypeModal = () => setShowTrashTypeModal(true);
  const handleCloseTrashTypeModal = () => setShowTrashTypeModal(false);
  return /* @__PURE__ */ jsxs("div", { style: containerStyle, children: [
    /* @__PURE__ */ jsx(
      HeroSection,
      {
        onStart,
        onShowHowToPlay: handleShowHowToPlayModal,
        onShowTrashType: handleShowTrashTypeModal
      }
    ),
    /* @__PURE__ */ jsx(LeaderboardSection, {}),
    /* @__PURE__ */ jsx(RewardSection, {}),
    showHowToPlayModal && /* @__PURE__ */ jsx(HowToPlayModal, { onClose: handleCloseHowToPlayModal }),
    showTrashTypeModal && /* @__PURE__ */ jsx(TrashTypeModal, { onClose: handleCloseTrashTypeModal }),
    " "
  ] });
}
const containerStyle = {
  fontFamily: "'Poppins', sans-serif",
  height: "100%",
  width: "100%",
  boxSizing: "border-box",
  display: "flex",
  flexDirection: "column",
  alignItems: "center"
};
const dummyLeaderboard = [
  { rank: 1, username: "User1", score: 1e3 },
  { rank: 2, username: "User2", score: 900 },
  { rank: 3, username: "User3", score: 800 },
  { rank: 4, username: "User4", score: 750 },
  { rank: 5, username: "User5", score: 700 },
  { rank: 6, username: "User6", score: 650 },
  { rank: 7, username: "User7", score: 600 },
  { rank: 8, username: "User8", score: 550 },
  { rank: 9, username: "User9", score: 500 },
  { rank: 10, username: "User10", score: 450 }
];
function EndScreen({ score, onRestart }) {
  const dummyRewards = [
    { id: 1, name: "Reward 1", points: 1e3 },
    { id: 2, name: "Reward 2", points: 2e3 },
    { id: 3, name: "Reward 3", points: 3e3 }
  ];
  return /* @__PURE__ */ jsxs("div", { style: endScreenContainerStyle, children: [
    /* @__PURE__ */ jsx("div", { style: headerStyle, children: /* @__PURE__ */ jsx("button", { style: backButtonEndScreenStyle, onClick: onRestart, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) }) }),
    /* @__PURE__ */ jsxs("div", { style: leaderboardSectionStyle, children: [
      /* @__PURE__ */ jsx("h2", { style: { marginBottom: "20px", color: "#111" }, children: "Leaderboard" }),
      /* @__PURE__ */ jsx("div", { style: top3ContainerStyle, children: dummyLeaderboard.slice(0, 3).map((player, index) => /* @__PURE__ */ jsxs("div", { style: top3CardStyle(player.rank), children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTrophy, style: trophyStyle(player.rank) }),
        /* @__PURE__ */ jsx("h3", { style: rankNumberTop3Style, children: player.rank }),
        /* @__PURE__ */ jsx("div", { style: usernameIconContainerStyle, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faUser, style: usernameIconStyle }) }),
        /* @__PURE__ */ jsx("p", { style: usernameTop3Style, children: player.username }),
        /* @__PURE__ */ jsxs("p", { style: scoreTop3Style, children: [
          player.score,
          " Points"
        ] })
      ] }, player.rank)) }),
      /* @__PURE__ */ jsx("div", { style: otherRanksContainerStyle, children: dummyLeaderboard.slice(3).map((player) => /* @__PURE__ */ jsxs("div", { style: rankItemStyle, children: [
        /* @__PURE__ */ jsx("span", { style: rankNumberStyle, children: player.rank }),
        /* @__PURE__ */ jsxs("div", { style: { display: "flex", alignItems: "center", gap: "10px", flex: 1 }, children: [
          /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faUser, style: rankIconStyle }),
          /* @__PURE__ */ jsx("span", { style: usernameStyle, children: player.username })
        ] }),
        /* @__PURE__ */ jsxs("span", { style: scoreStyle, children: [
          /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faStar, style: { color: "#facc15", marginRight: "5px" } }),
          player.score,
          " Pts"
        ] })
      ] }, player.rank)) })
    ] }),
    /* @__PURE__ */ jsxs("div", { style: rewardSectionStyle, children: [
      /* @__PURE__ */ jsx("h2", { style: { color: "#333" }, children: "Klaim Reward" }),
      /* @__PURE__ */ jsx("div", { style: rewardBoxesContainerStyle, children: dummyRewards.map((reward) => /* @__PURE__ */ jsxs("div", { style: rewardBoxStyle, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faGift, style: giftIconStyle }),
        /* @__PURE__ */ jsx("p", { style: { margin: "0" }, children: reward.name }),
        /* @__PURE__ */ jsxs("p", { style: { margin: "4px 0" }, children: [
          reward.points,
          " pts"
        ] }),
        /* @__PURE__ */ jsx("button", { style: claimButtonStyle, children: "Klaim" })
      ] }, reward.id)) })
    ] })
  ] });
}
const endScreenContainerStyle = {
  background: "linear-gradient(to bottom, #dcfce7, #f0fdf4)",
  padding: "40px",
  borderRadius: "24px",
  fontFamily: "'Poppins', sans-serif",
  height: "100%",
  boxSizing: "border-box",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  boxShadow: "0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)"
};
const headerStyle = {
  display: "flex",
  alignItems: "center",
  justifyContent: "flex-end",
  width: "100%",
  marginBottom: "20px"
};
const backButtonEndScreenStyle = {
  background: "#fee2e2",
  color: "#ef4444",
  border: "none",
  width: "40px",
  height: "40px",
  borderRadius: "50%",
  cursor: "pointer",
  fontSize: "18px"
};
const leaderboardSectionStyle = {
  borderRadius: "16px",
  padding: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  marginBottom: "20px",
  textAlign: "center",
  backgroundColor: "#f0fdf4",
  width: "100%",
  maxWidth: "960px"
};
const top3ContainerStyle = {
  display: "flex",
  justifyContent: "space-around",
  alignItems: "flex-end",
  marginBottom: "20px"
};
const top3CardStyle = (rank) => ({
  width: "120px",
  padding: "16px 12px",
  background: rank === 1 ? "#b91c1c" : rank === 2 ? "#a16207" : "#57534e",
  color: "white",
  borderRadius: "12px",
  position: "relative",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  transform: rank === 1 ? "scale(1.15) translateY(-20px)" : "scale(1)",
  zIndex: rank === 1 ? 10 : 1,
  boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`
});
const rankNumberTop3Style = {
  margin: "0",
  fontSize: "2.5em",
  fontWeight: "800"
};
const usernameIconContainerStyle = {
  width: "50px",
  height: "50px",
  borderRadius: "50%",
  background: "rgba(255,255,255,0.2)",
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  marginBottom: "8px"
};
const usernameIconStyle = {
  fontSize: "2em",
  color: "white"
};
const usernameTop3Style = {
  margin: "0",
  fontWeight: "bold",
  fontSize: "1em"
};
const scoreTop3Style = {
  margin: "4px 0 0",
  fontSize: "0.8em",
  color: "#facc15"
};
const trophyStyle = (rank) => ({
  fontSize: "3em",
  color: rank === 1 ? "#facc15" : rank === 2 ? "#e5e7eb" : "#a16207",
  position: "absolute",
  top: "-20px",
  left: "50%",
  transform: "translateX(-50%)",
  filter: "drop-shadow(0 2px 2px rgba(0,0,0,0.2))"
});
const otherRanksContainerStyle = {
  background: "#fee2e2",
  padding: "10px",
  borderRadius: "12px",
  border: "2px solid #ef4444"
};
const rankItemStyle = {
  display: "flex",
  alignItems: "center",
  background: "#fef2f2",
  borderRadius: "8px",
  padding: "10px 15px",
  justifyContent: "space-between",
  marginBottom: "5px"
};
const rankNumberStyle = {
  fontWeight: "bold",
  color: "#6b7280",
  width: "20px",
  textAlign: "center"
};
const usernameStyle = {
  flex: 1,
  textAlign: "left",
  color: "#333",
  fontWeight: "bold"
};
const rankIconStyle = {
  fontSize: "1em",
  color: "#9ca3af"
};
const scoreStyle = {
  fontWeight: "bold",
  color: "#facc15",
  fontSize: "0.9em"
};
const rewardSectionStyle = {
  background: "white",
  borderRadius: "16px",
  padding: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  textAlign: "center",
  backgroundColor: "#f0fdf4",
  width: "100%",
  maxWidth: "960px",
  marginTop: "20px"
};
const rewardBoxesContainerStyle = {
  display: "flex",
  justifyContent: "space-around",
  gap: "20px",
  marginTop: "20px"
};
const rewardBoxStyle = {
  flex: 1,
  background: "#f9fafb",
  borderRadius: "12px",
  padding: "20px",
  border: "1px solid #e5e7eb",
  textAlign: "center"
};
const giftIconStyle = {
  fontSize: "3em",
  color: "#22c55e",
  marginBottom: "10px"
};
const claimButtonStyle = {
  marginTop: "10px",
  padding: "8px 16px",
  background: "#10b981",
  color: "white",
  border: "none",
  borderRadius: "6px",
  cursor: "pointer",
  fontWeight: "bold"
};
function GameWrapper() {
  const [gameState, setGameState] = useState("start");
  const [finalScore, setFinalScore] = useState(0);
  const [gameKey, setGameKey] = useState(0);
  const handleStartGame = () => {
    setGameState("playing");
    setGameKey((prevKey) => prevKey + 1);
  };
  const handleEndGame = () => {
    setFinalScore(0);
    setGameState("start");
  };
  const handleRestartGame = () => {
    setGameState("start");
    setFinalScore(0);
  };
  const gameProps = {
    onGameEnd: handleEndGame
    // Teruskan fungsi baru
  };
  switch (gameState) {
    case "playing":
      return /* @__PURE__ */ jsx(PilahSampahGame, { ...gameProps }, gameKey);
    case "end":
      return /* @__PURE__ */ jsx(EndScreen, { score: finalScore, onRestart: handleRestartGame });
    case "start":
    default:
      return /* @__PURE__ */ jsx(StartScreen, { onStart: handleStartGame });
  }
}
window.Alpine = Alpine;
Alpine.start();
const container = document.getElementById("pilah-sampah");
if (container) {
  const root = createRoot(container);
  root.render(
    /* @__PURE__ */ jsx(React.StrictMode, { children: /* @__PURE__ */ jsx(GameWrapper, {}) })
  );
}
