import { jsxs, jsx } from "react/jsx-runtime";
import { useState, useEffect, useMemo } from "react";
import { usePage } from "@inertiajs/react";
import { P as PilahSampahGame } from "../app2.js";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faInfoCircle, faPlay, faRecycle, faTrophy, faStar, faTimes, faUser, faGift, faRotate } from "@fortawesome/free-solid-svg-icons";
import "react-dom/client";
import "@dnd-kit/core";
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
  border: "1px solid rgba(255, 255, 255, 0.2)",
  backdropFilter: "blur(10px)",
  borderRadius: "20px",
  padding: "50px 70px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  backgroundColor: "white"
};
const imageContainerStyle = {
  marginBottom: "20px"
};
const gameTitleStyle = {
  fontSize: "min(6vw, 3em)",
  fontWeight: "800",
  marginBottom: "30px",
  color: "#16a34a",
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const buttonContainerStyle = {
  display: "flex",
  gap: "min(20px, 3vw)",
  justifyContent: "center",
  flexWrap: "wrap"
};
const baseButtonStyle = {
  padding: "12px 24px",
  borderRadius: "12px",
  fontSize: "min(3vw, 1em)",
  fontWeight: "bold",
  cursor: "pointer",
  border: "none",
  boxShadow: "0 4px 12px rgba(0,0,0,0.2)",
  transition: "transform 0.3s ease, box-shadow 0.3s ease",
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  // Perbaikan: Menengahkan konten secara horizontal
  textTransform: "uppercase",
  whiteSpace: "nowrap",
  minWidth: "160px"
  // Menetapkan lebar minimum agar tidak terlalu kecil
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
const Avatar = ({ player }) => {
  const label = (player == null ? void 0 : player.username) || "User";
  const url = (player == null ? void 0 : player.avatar_url) ?? ((player == null ? void 0 : player.profile_photo_path) ? `/storage/${String(player.profile_photo_path).replace(/^\/+/, "")}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(label)}&background=random`);
  return /* @__PURE__ */ jsx(
    "img",
    {
      src: url,
      alt: label,
      style: { width: 40, height: 40, borderRadius: "50%", objectFit: "cover" },
      onError: (e) => {
        e.currentTarget.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(label)}&background=random`;
      }
    }
  );
};
function LeaderboardSection({ leaderboardData }) {
  const [isMobile, setIsMobile] = useState(
    typeof window !== "undefined" ? window.innerWidth < 768 : false
  );
  useEffect(() => {
    const onResize = () => setIsMobile(window.innerWidth < 768);
    if (typeof window !== "undefined") {
      window.addEventListener("resize", onResize);
      return () => window.removeEventListener("resize", onResize);
    }
  }, []);
  if (!Array.isArray(leaderboardData) || leaderboardData.length === 0) {
    return /* @__PURE__ */ jsx("p", { children: "No leaderboard data available" });
  }
  const getPoints = (u) => {
    var _a;
    const val = Array.isArray(u == null ? void 0 : u.userpoints) && u.userpoints.length > 0 ? (_a = u.userpoints[0]) == null ? void 0 : _a.points : 0;
    return Number(val) || 0;
  };
  const sorted = useMemo(() => {
    return [...leaderboardData].sort((a, b) => getPoints(b) - getPoints(a));
  }, [leaderboardData]);
  const rankedData = useMemo(() => {
    return sorted.map((item, index) => ({ ...item, rank: index + 1 }));
  }, [sorted]);
  const top3 = rankedData.slice(0, 3);
  const top3Rankings = top3.length === 3 ? [top3[1], top3[0], top3[2]] : top3;
  const otherRanks = rankedData.slice(3);
  const dynamicTitleMargin = isMobile ? "24px" : "64px";
  const renderRankedList = (data) => /* @__PURE__ */ jsx("div", { style: otherRanksContainerStyle$1, children: data.map((player) => {
    const label = (player == null ? void 0 : player.username) || "User";
    console.log("User:", label, "Userpoints:", player.userpoints);
    return /* @__PURE__ */ jsxs("div", { style: rankItemStyle$1, children: [
      /* @__PURE__ */ jsx("span", { style: rankNumberStyle$1, children: player.rank }),
      /* @__PURE__ */ jsxs("div", { style: { display: "flex", alignItems: "center", gap: "10px", flex: 1 }, children: [
        /* @__PURE__ */ jsx(Avatar, { player }),
        /* @__PURE__ */ jsx("span", { style: usernameStyle$1, children: label })
      ] }),
      /* @__PURE__ */ jsxs("span", { style: scoreStyle$1, children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faStar, style: { color: "#facc15", marginRight: "5px" } }),
        getPoints(player),
        " Pts"
      ] })
    ] }, `${player.id ?? label}-${player.rank}`);
  }) });
  return /* @__PURE__ */ jsxs("div", { style: leaderboardSectionStyle$1, children: [
    /* @__PURE__ */ jsx("h2", { style: { ...leaderboardTitleStyle, marginBottom: dynamicTitleMargin }, children: "Leaderboard" }),
    !isMobile && top3Rankings.length > 0 && /* @__PURE__ */ jsx("div", { style: top3ContainerStyle$1, children: top3Rankings.map((player) => {
      const label = (player == null ? void 0 : player.username) || "User";
      return /* @__PURE__ */ jsxs("div", { style: top3CardStyle$1(player.rank), children: [
        /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTrophy, style: trophyStyle$1(player.rank) }),
        /* @__PURE__ */ jsx("h3", { style: rankNumberTop3Style$1, children: player.rank }),
        /* @__PURE__ */ jsx("div", { style: usernameIconContainerStyle$1, children: /* @__PURE__ */ jsx(Avatar, { player }) }),
        /* @__PURE__ */ jsx("p", { style: usernameTop3Style$1, children: label }),
        /* @__PURE__ */ jsxs("p", { style: scoreTop3Style$1, children: [
          getPoints(player),
          " Points"
        ] })
      ] }, `${player.id ?? label}-top-${player.rank}`);
    }) }),
    renderRankedList(isMobile ? rankedData : otherRanks)
  ] });
}
const leaderboardSectionStyle$1 = {
  width: "100%",
  padding: "min(50px, 5vw) min(70px, 8vw)",
  background: "white",
  borderRadius: "20px",
  boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
  textAlign: "center",
  boxSizing: "border-box"
};
const leaderboardTitleStyle = {
  color: "#16a34a",
  fontSize: "min(5vw, 2em)",
  fontWeight: "800",
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const top3ContainerStyle$1 = {
  display: "flex",
  justifyContent: "center",
  alignItems: "flex-end",
  gap: "min(20px, 3vw)",
  marginBottom: "20px",
  flexWrap: "wrap"
};
const top3CardStyle$1 = (rank) => ({
  width: "min(120px, 25vw)",
  padding: "16px 12px",
  background: rank === 1 ? "#b91c1c" : rank === 2 ? "#a16207" : "#57534e",
  color: "white",
  borderRadius: "12px",
  position: "relative",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  minHeight: rank === 1 ? "min(220px, 35vw)" : rank === 2 ? "min(200px, 30vw)" : "min(180px, 25vw)",
  justifyContent: "space-between",
  boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`,
  transition: "all 0.3s ease",
  flex: "1 1 auto"
});
const rankNumberTop3Style$1 = { margin: 0, fontSize: "min(6vw, 2.5em)", fontWeight: "800" };
const usernameIconContainerStyle$1 = {
  width: "50px",
  height: "50px",
  borderRadius: "50%",
  background: "rgba(255,255,255,0.2)",
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  overflow: "hidden"
};
const usernameTop3Style$1 = { margin: 0, fontWeight: "bold", fontSize: "min(3vw, 1em)" };
const scoreTop3Style$1 = { margin: "4px 0 0", fontSize: "min(2.5vw, 0.8em)", color: "#facc15" };
const trophyStyle$1 = (rank) => ({
  fontSize: "min(7vw, 3em)",
  color: rank === 1 ? "#facc15" : rank === 2 ? "#e5e7eb" : "#a16207",
  position: "absolute",
  top: "min(-4vw, -20px)",
  left: "50%",
  transform: "translateX(-50%)",
  filter: "drop-shadow(0 2px 2px rgba(0,0,0,0.2))"
});
const otherRanksContainerStyle$1 = {
  background: "white",
  padding: "10px",
  borderRadius: "12px",
  border: "1px solid rgba(0,0,0,0.1)"
};
const rankItemStyle$1 = {
  display: "flex",
  alignItems: "center",
  background: "rgba(243, 244, 246, 0.5)",
  borderRadius: "8px",
  padding: "10px 15px",
  justifyContent: "space-between",
  marginBottom: "5px",
  flexWrap: "wrap"
};
const rankNumberStyle$1 = { fontWeight: "bold", color: "#1f2937", width: "20px", textAlign: "center" };
const usernameStyle$1 = {
  flex: 1,
  textAlign: "left",
  color: "#1f2937",
  fontWeight: "bold",
  whiteSpace: "nowrap",
  overflow: "hidden",
  textOverflow: "ellipsis"
};
const scoreStyle$1 = { fontWeight: "bold", color: "#facc15", fontSize: "0.9em" };
function HowToPlayModal({ onClose }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle$2, children: /* @__PURE__ */ jsxs("div", { style: modalStyle$2, children: [
    /* @__PURE__ */ jsx("button", { style: closeButtonStyle$1, onClick: onClose, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) }),
    /* @__PURE__ */ jsx("h2", { style: titleStyle$2, children: "Cara Bermain" }),
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
const overlayStyle$2 = {
  position: "fixed",
  // Perbaikan: Mengubah ke fixed agar memenuhi seluruh layar
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: "rgba(0, 0, 0, 0.6)",
  backdropFilter: "blur(5px)",
  WebkitBackdropFilter: "blur(5px)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1e3
};
const modalStyle$2 = {
  background: "white",
  padding: "30px 40px",
  borderRadius: "20px",
  width: "400px",
  maxWidth: "90%",
  textAlign: "center",
  color: "#1f2937",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const closeButtonStyle$1 = {
  position: "absolute",
  top: "10px",
  right: "10px",
  background: "none",
  border: "none",
  color: "#4b5563",
  fontSize: "20px",
  cursor: "pointer"
};
const titleStyle$2 = {
  fontSize: "2em",
  fontWeight: "bold",
  marginBottom: "8px",
  color: "#16a34a",
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const subtitleStyle$1 = {
  fontSize: "0.9em",
  fontWeight: "300",
  marginBottom: "20px",
  color: "#4b5563"
};
const listStyle$1 = {
  textAlign: "left",
  fontSize: "0.9em",
  lineHeight: "1.8",
  marginBottom: "30px",
  paddingLeft: "20px",
  color: "#1f2937",
  listStyleType: "decimal"
};
const backButtonStyle$1 = {
  background: "#10b981",
  color: "white",
  padding: "10px 24px",
  border: "none",
  borderRadius: "8px",
  cursor: "pointer",
  fontSize: "0.9em",
  fontWeight: "bold",
  transition: "transform 0.2s ease",
  "&:hover": {
    transform: "scale(1.05)"
  }
};
function TrashTypeModal({ onClose }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle$1, children: /* @__PURE__ */ jsxs("div", { style: modalStyle$1, children: [
    /* @__PURE__ */ jsx("button", { style: closeButtonStyle, onClick: onClose, children: /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faTimes }) }),
    /* @__PURE__ */ jsx("h2", { style: titleStyle$1, children: "Jenis Sampah" }),
    /* @__PURE__ */ jsx("p", { style: subtitleStyle, children: "Memisahkan sampah ke dalam tiga jenis tempat sampah yang benar" }),
    /* @__PURE__ */ jsxs("ol", { style: listStyle, children: [
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsx("div", { style: listItemHeaderStyle, children: /* @__PURE__ */ jsx("span", { style: { color: "#16a34a", fontWeight: "bold" }, children: "Organik" }) }),
        /* @__PURE__ */ jsx("p", { style: listItemDescriptionStyle, children: "Sampah yang bisa terurai, seperti sisa makanan, daun, kulit buah." })
      ] }),
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsx("div", { style: listItemHeaderStyle, children: /* @__PURE__ */ jsx("span", { style: { color: "#f59e0b", fontWeight: "bold" }, children: "Anorganik" }) }),
        /* @__PURE__ */ jsx("p", { style: listItemDescriptionStyle, children: "Sampah yang sulit terurai, seperti plastik, botol minum, kertas." })
      ] }),
      /* @__PURE__ */ jsxs("li", { children: [
        /* @__PURE__ */ jsxs("div", { style: listItemHeaderStyle, children: [
          /* @__PURE__ */ jsx("span", { style: { color: "#ef4444", fontWeight: "bold" }, children: "B3" }),
          " (Bahan Berbahaya & Beracun)"
        ] }),
        /* @__PURE__ */ jsx("p", { style: listItemDescriptionStyle, children: "Sampah berbahaya seperti baterai, kaca pecah, obat kadaluarsa." })
      ] })
    ] }),
    /* @__PURE__ */ jsx("button", { style: backButtonStyle, onClick: onClose, children: "Kembali" })
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
  WebkitBackdropFilter: "blur(5px)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1e3
};
const modalStyle$1 = {
  background: "white",
  padding: "30px 40px",
  borderRadius: "20px",
  width: "400px",
  maxWidth: "90%",
  textAlign: "center",
  color: "#1f2937",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const closeButtonStyle = {
  position: "absolute",
  top: "10px",
  right: "10px",
  background: "none",
  border: "none",
  color: "#4b5563",
  fontSize: "20px",
  cursor: "pointer"
};
const titleStyle$1 = {
  fontSize: "2em",
  fontWeight: "bold",
  marginBottom: "8px",
  color: "#16a34a",
  textShadow: "1px 1px 2px rgba(0,0,0,0.1)"
};
const subtitleStyle = {
  fontSize: "0.9em",
  fontWeight: "300",
  marginBottom: "20px",
  color: "#4b5563"
};
const listStyle = {
  textAlign: "left",
  fontSize: "0.9em",
  lineHeight: "1.8",
  marginBottom: "30px",
  paddingLeft: "20px",
  color: "#1f2937",
  listStyleType: "decimal"
};
const listItemHeaderStyle = {
  display: "block",
  // Mengubah display agar setiap judul memiliki baris sendiri
  fontWeight: "bold"
};
const listItemDescriptionStyle = {
  display: "block",
  // Mengubah display agar setiap deskripsi memiliki baris sendiri
  margin: 0,
  lineHeight: "1.4"
};
const backButtonStyle = {
  background: "#10b981",
  color: "white",
  padding: "10px 24px",
  border: "none",
  borderRadius: "8px",
  cursor: "pointer",
  fontSize: "0.9em",
  fontWeight: "bold",
  transition: "transform 0.2s ease",
  "&:hover": {
    transform: "scale(1.05)"
  }
};
function StartScreen({ onStart, leaderboardData }) {
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
    /* @__PURE__ */ jsx(LeaderboardSection, { leaderboardData }),
    "  ",
    showHowToPlayModal && /* @__PURE__ */ jsx(HowToPlayModal, { onClose: handleCloseHowToPlayModal }),
    showTrashTypeModal && /* @__PURE__ */ jsx(TrashTypeModal, { onClose: handleCloseTrashTypeModal })
  ] });
}
const containerStyle = {
  fontFamily: "'Poppins', sans-serif",
  width: "100%",
  boxSizing: "border-box",
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  gap: "24px"
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
function FullscreenPrompt({ onContinue }) {
  return /* @__PURE__ */ jsx("div", { style: overlayStyle, children: /* @__PURE__ */ jsxs("div", { style: modalStyle, children: [
    /* @__PURE__ */ jsx("h2", { style: titleStyle, children: "Putar Layar untuk Bermain!" }),
    /* @__PURE__ */ jsx(FontAwesomeIcon, { icon: faRotate, style: iconStyle }),
    /* @__PURE__ */ jsx("p", { style: messageStyle, children: "Untuk pengalaman terbaik, putar perangkat Anda ke mode lanskap dan klik tombol di bawah ini." }),
    /* @__PURE__ */ jsx("button", { style: buttonStyle, onClick: onContinue, children: "Mulai Fullscreen" })
  ] }) });
}
const overlayStyle = {
  position: "fixed",
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  background: "rgba(0,0,0,0.8)",
  display: "flex",
  flexDirection: "column",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 9999
};
const modalStyle = {
  background: "white",
  padding: "40px",
  borderRadius: "20px",
  width: "350px",
  maxWidth: "90%",
  textAlign: "center",
  fontFamily: "'Poppins', sans-serif",
  position: "relative",
  boxShadow: "0 10px 25px rgba(0,0,0,0.3)"
};
const titleStyle = {
  fontSize: "1.8em",
  fontWeight: "bold",
  marginBottom: "10px",
  color: "#1f2937"
};
const iconStyle = {
  fontSize: "5em",
  color: "#4b5563",
  margin: "20px 0",
  transform: "rotate(90deg)"
};
const messageStyle = {
  fontSize: "0.9em",
  fontWeight: "400",
  marginBottom: "30px",
  color: "#4b5563",
  lineHeight: "1.5"
};
const buttonStyle = {
  padding: "12px 28px",
  borderRadius: "10px",
  fontSize: "1em",
  fontWeight: "bold",
  cursor: "pointer",
  border: "none",
  background: "#10b981",
  color: "white",
  transition: "transform 0.2s ease, box-shadow 0.2s ease"
};
function GameWrapper() {
  const [gameState, setGameState] = useState("start");
  const [finalScore, setFinalScore] = useState(0);
  const [gameKey, setGameKey] = useState(0);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);
  const [isLandscape, setIsLandscape] = useState(window.innerWidth > window.innerHeight);
  const { leaderboard } = usePage().props;
  console.log(leaderboard);
  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 768);
      setIsLandscape(window.innerWidth > window.innerHeight);
    };
    window.addEventListener("resize", handleResize);
    window.addEventListener("orientationchange", handleResize);
    return () => {
      window.removeEventListener("resize", handleResize);
      window.removeEventListener("orientationchange", handleResize);
    };
  }, []);
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
  const handleFullscreen = () => {
    const elem = document.documentElement;
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen();
    }
    if (screen.orientation.lock) {
      screen.orientation.lock("landscape").catch((err) => {
        console.error("Gagal mengunci orientasi layar:", err);
      });
    }
  };
  const gameProps = {
    onGameEnd: handleEndGame
  };
  const wrapperStyle = {
    background: "linear-gradient(to bottom, #dcfce7, #f0fdf4)",
    minHeight: "100vh",
    fontFamily: "'Poppins', sans-serif",
    padding: "24px",
    boxSizing: "border-box"
  };
  if (gameState === "playing" && isMobile && !isLandscape) {
    return /* @__PURE__ */ jsx(FullscreenPrompt, { onContinue: handleFullscreen });
  }
  switch (gameState) {
    case "playing":
      return /* @__PURE__ */ jsx("div", { style: wrapperStyle, children: /* @__PURE__ */ jsx(PilahSampahGame, { ...gameProps }, gameKey) });
    case "end":
      return /* @__PURE__ */ jsx("div", { style: wrapperStyle, children: /* @__PURE__ */ jsx(EndScreen, { score: finalScore, onRestart: handleRestartGame }) });
    case "start":
    default:
      return /* @__PURE__ */ jsx("div", { style: wrapperStyle, children: /* @__PURE__ */ jsx(StartScreen, { onStart: handleStartGame, leaderboardData: leaderboard }) });
  }
}
function Test() {
  const { leaderboard } = usePage().props;
  return /* @__PURE__ */ jsx("div", { children: /* @__PURE__ */ jsx(GameWrapper, { leaderboardData: leaderboard }) });
}
export {
  Test as default
};
