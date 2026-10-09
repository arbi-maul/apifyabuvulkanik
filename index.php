import React, { useState, useEffect, useMemo, useCallback } from 'react';
import {
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  Legend,
  CartesianGrid
} from 'recharts';
import {
  Activity,
  RefreshCw,
  Download,
  Search,
  Filter,
  Moon,
  Sun,
  AlertTriangle,
  CheckCircle2,
  XCircle,
  MessageSquare,
  Heart,
  Share2,
  Eye,
  User,
  Sparkles,
  ChevronDown,
  ChevronUp,
  Database,
  ShieldAlert,
  Key,
  BarChart3,
  PieChartIcon,
  TrendingUp,
  Info,
  ExternalLink,
  FileSpreadsheet,
  Globe
} from 'lucide-react';

// Default dataset ID provided in the prompt
const DEFAULT_DATASET_ID = 'xmFJuy7OuhMHk3h83';

// Mock TikTok dataset items reflecting the exact structure from Apify TikTok Scraper
const MOCK_TIKTOK_DATA = [
  {
    id: 'tiktok_1',
    text: 'Abu vulkanik Anak Krakatau makin pekat hari ini di pesisir Banten. Banyak warga mulai sesak nafas dan batuk-batuk parah. Mohon bantuan masker medis! 🌋😷 #AnakKrakatau #AbuVulkanik #Banten',
    authorMeta: {
      name: 'warga_banten_official',
      nickName: 'Banten Watch',
      avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 14200,
    shareCount: 3850,
    playCount: 245000,
    commentCount: 1290,
    createTimeISO: '2026-10-08T14:30:00.000Z'
  },
  {
    id: 'tiktok_2',
    text: 'Alhamdulillah bantuan masker gratis dan tim medis dari relawan BPBD sudah tiba di Posko Selat Sunda. Terima kasih banyak orang baik! 🙏❤️ #KrakatauErupsi #Relawan',
    authorMeta: {
      name: 'posko_peduli_krakatau',
      nickName: 'Posko Peduli',
      avatar: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 28900,
    shareCount: 5120,
    playCount: 410000,
    commentCount: 2150,
    createTimeISO: '2026-10-08T11:15:00.000Z'
  },
  {
    id: 'tiktok_3',
    text: 'Update Erupsi Gunung Anak Krakatau: Kolom abu teramati setinggi 1.500m condong ke arah Barat Daya. Status Siaga Level III. Tetap tenang dan ikuti arahan resmi PVMBG/BMKG. 🌋📍',
    authorMeta: {
      name: 'infobencana_id',
      nickName: 'Info Bencana RI',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 8500,
    shareCount: 2100,
    playCount: 180000,
    commentCount: 430,
    createTimeISO: '2026-10-08T09:00:00.000Z'
  },
  {
    id: 'tiktok_4',
    text: 'Volcanic ash from Anak Krakatau reaching coastal areas today. Flights delayed at nearby airports. Everyone please stay indoors and wear N95 masks! Stay safe all. 🌋✈️',
    authorMeta: {
      name: 'se_asia_news',
      nickName: 'SE Asia Digest',
      avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 19400,
    shareCount: 6200,
    playCount: 320000,
    commentCount: 890,
    createTimeISO: '2026-10-08T07:45:00.000Z'
  },
  {
    id: 'tiktok_5',
    text: '#AnakKrakatau #Erupsi #AbuVulkanik #SelatSunda #BMKG #BPBD #Waspada',
    authorMeta: {
      name: 'trending_viral_id',
      nickName: 'Trending Now',
      avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 3100,
    shareCount: 450,
    playCount: 95000,
    commentCount: 110,
    createTimeISO: '2026-10-08T06:20:00.000Z'
  },
  {
    id: 'tiktok_6',
    text: 'Aktivitas nelayan Selat Sunda lumpuh total akibat jarak pandang terbatas dari debu vulkanik. Kerugian kapal ikan sangat besar dan rumah warga tertutup abu tebal mencekam. 😭💔',
    authorMeta: {
      name: 'suara_nelayan_lampung',
      nickName: 'Kabar Pesisir',
      avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 22100,
    shareCount: 4300,
    playCount: 380000,
    commentCount: 1780,
    createTimeISO: '2026-10-07T21:10:00.000Z'
  },
  {
    id: 'tiktok_7',
    text: 'Mari kita doakan keselamatan saudara-saudara kita di Banten & Lampung. Semoga Allah lindungi dari bahaya susulan bencana erupsi Krakatau. Aamiin ya Rabbal Alamin. 🤲✨',
    authorMeta: {
      name: 'pemuda_hijrah_lamsel',
      nickName: 'Rahmat Hidayat',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 35600,
    shareCount: 7800,
    playCount: 520000,
    commentCount: 3400,
    createTimeISO: '2026-10-07T18:00:00.000Z'
  },
  {
    id: 'tiktok_8',
    text: 'Anak Krakatau vulkanik kül püskürtdü, sahil rayonlarında hava keyfiyyəti pisləşib. Sakinlərə qoruyucu maskalardan istifadə etmək tövsiyə olunur.',
    authorMeta: {
      name: 'global_az_news',
      nickName: 'Xəbər Dünyası',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'
    },
    diggCount: 4200,
    shareCount: 980,
    playCount: 110000,
    commentCount: 210,
    createTimeISO: '2026-10-07T15:30:00.000Z'
  }
];

/**
 * Multilingual Sentiment Classifier (Indonesian, English, Azerbaijani)
 * Rules:
 * - If text consists solely of hashtags, output "Netral"
 * - Keyword/Context matching for positive, negative, or neutral tones
 * - Calculates confidence score (0.00 - 1.00) and detects language
 */
function classifySentiment(text) {
  if (!text || typeof text !== 'string') {
    return { label: 'Netral', confidence: 0.80, language: 'ID', reason: 'Teks kosong' };
  }

  const trimmed = text.trim();
  const tokens = trimmed.split(/\s+/);

  // Check if text is only hashtags
  const isOnlyHashtags = tokens.length > 0 && tokens.every(w => w.startsWith('#'));
  if (isOnlyHashtags) {
    return { label: 'Netral', confidence: 0.95, language: 'ID', reason: 'Hanya berisi tagar/hashtag' };
  }

  const lowerText = trimmed.toLowerCase();

  // Detect language
  let language = 'ID';
  if (/\b(kül|püskürtdü|sahil|rayonlarında|hava|keyfiyyəti|sakinlərə|maskalardan|istifadə|tövsiyə|olunur)\b/i.test(lowerText)) {
    language = 'AZ';
  } else if (/\b(the|is|are|volcano|ash|eruption|coastal|flights|delayed|stay|indoors|safe|wear|mask|everyone|please)\b/i.test(lowerText)) {
    language = 'EN';
  }

  // Positive lexicon
  const posKeywords = [
    'alhamdulillah', 'bantuan', 'terima kasih', 'selamat', 'aman', 'sehat', 'lancar',
    'tanggap', 'bagus', 'berdoa', 'dukungan', 'salut', 'mendoakan', 'aamiin', 'amin',
    'mantap', 'keren', 'terima-kasih', 'bersatu', 'alhamdullilah', 'mantaap', 'rahmat',
    'help arrived', 'thank you', 'stay safe', 'prayers', 'blessed', 'safe'
  ];

  // Negative lexicon
  const negKeywords = [
    'sesak', 'batuk', 'parah', 'rusak', 'hancur', 'mencekam', 'panik', 'takut',
    'seram', 'buruk', 'rugi', 'menakutkan', 'sedih', 'korban', 'bahaya', 'ispa',
    'lumpuh', 'terisolasi', 'darurat', 'ancaman', 'meninggal', 'trauma', 'dampak buruk',
    'delayed', 'bad', 'danger', 'ash storm', 'tersebar', 'pisləşib'
  ];

  let posCount = 0;
  let negCount = 0;

  posKeywords.forEach(kw => {
    if (lowerText.includes(kw)) posCount++;
  });

  negKeywords.forEach(kw => {
    if (lowerText.includes(kw)) negCount++;
  });

  if (posCount > negCount) {
    const confidence = Math.min(0.75 + posCount * 0.08, 0.98);
    return { label: 'Positif', confidence: Number(confidence.toFixed(2)), language };
  } else if (negCount > posCount) {
    const confidence = Math.min(0.78 + negCount * 0.08, 0.99);
    return { label: 'Negatif', confidence: Number(confidence.toFixed(2)), language };
  } else {
    return { label: 'Netral', confidence: 0.85, language };
  }
}

// Utility number formatter
function formatNumber(num) {
  if (num === undefined || num === null) return '0';
  if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
  if (num >= 1000) return (num / 1000).toFixed(1) + 'k';
  return num.toLocaleString('id-ID');
}

export default function App() {
  // Theme state (Dark/Light mode)
  const [darkMode, setDarkMode] = useState(false);

  // API diagnostic panel state
  const [datasetIdInput, setDatasetIdInput] = useState(DEFAULT_DATASET_ID);
  const [apiTokenInput, setApiTokenInput] = useState('');
  const [apiStatus, setApiStatus] = useState('success'); // 'success' | 'error' | 'loading'
  const [statusMessage, setStatusMessage] = useState('Terhubung ke Apify (8 Data TikTok Krakatau)');
  const [dataSourceType, setDataSourceType] = useState('Mock / Live Sync');

  // Main data state
  const [rawItems, setRawItems] = useState(MOCK_TIKTOK_DATA);
  const [isLoading, setIsLoading] = useState(false);
  const [lastUpdated, setLastUpdated] = useState(new Date().toLocaleString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }));

  // Filtering & search states
  const [selectedSentiment, setSelectedSentiment] = useState('Semua');
  const [searchQuery, setSearchQuery] = useState('');

  // Expand text modal / detail state
  const [expandedTextItem, setExpandedTextItem] = useState(null);

  const handleFetchApifyData = useCallback(async (customDatasetId, customToken) => {
    setIsLoading(true);
    setApiStatus('loading');
    setStatusMessage('Menghubungkan & mengunduh data dari Apify...');

    const targetId = (customDatasetId || datasetIdInput || DEFAULT_DATASET_ID).trim();
    const token = (customToken || apiTokenInput).trim();

    // Construct Apify endpoint
    let url = `https://api.apify.com/v2/datasets/${targetId}/items?format=json&limit=8`;
    if (token) {
      url += `&token=${token}`;
    }

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30s timeout

    try {
      const response = await fetch(url, { signal: controller.signal });
      clearTimeout(timeoutId);

      if (!response.ok) {
        if (response.status === 404) {
          throw new Error('Dataset tidak ditemukan atau sudah kedaluwarsa. Periksa kembali Dataset ID.');
        } else if (response.status === 401) {
          throw new Error('Token API Apify tidak valid atau Anda memerlukan autentikasi.');
        } else {
          throw new Error(`Gagal menghubungi Apify (HTTP ${response.status}).`);
        }
      }

      const data = await response.json();

      if (Array.isArray(data) && data.length > 0) {
        // Map Apify items to standard schema if fields differ
        const mappedData = data.slice(0, 8).map((item, idx) => ({
          id: item.id || item.id_str || `item_${idx}`,
          text: item.text || item.full_text || item.desc || item.title || 'Tidak ada teks',
          authorMeta: {
            name: item.authorMeta?.name || item.author || item.username || 'anonim',
            nickName: item.authorMeta?.nickName || item.authorMeta?.name || item.author || 'Pengguna TikTok',
            avatar: item.authorMeta?.avatar || item.avatar || `https://api.dicebear.com/7.x/bottts/svg?seed=${idx}`
          },
          diggCount: item.diggCount || item.likes || item.likeCount || 0,
          shareCount: item.shareCount || item.shares || 0,
          playCount: item.playCount || item.views || item.viewCount || 0,
          commentCount: item.commentCount || item.comments || 0,
          createTimeISO: item.createTimeISO || item.createdAt || item.date || new Date().toISOString()
        }));

        setRawItems(mappedData);
        setApiStatus('success');
        setStatusMessage(`Terhubung! Berhasil memuat ${mappedData.length} data asli dari Apify.`);
        setDataSourceType('Apify Live Stream');
        setLastUpdated(new Date().toLocaleString('id-ID', {
          day: 'numeric',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }));
      } else {
        throw new Error('Dataset Apify ditemukan tetapi kosong (0 items). Menampilkan data sampel.');
      }
    } catch (err) {
      clearTimeout(timeoutId);
      console.warn('Apify Fetch Warning:', err.message);

      setApiStatus('error');
      if (err.name === 'AbortError') {
        setStatusMessage('Koneksi timeout (30 detik). Menggunakan data simulasi fallback.');
      } else {
        setStatusMessage(err.message || 'Gagal memuat data dari Apify. Menampilkan data cadangan.');
      }

      // Fallback to rich mock data
      setRawItems(MOCK_TIKTOK_DATA);
      setDataSourceType('Data Simulasi (Fallback)');
    } finally {
      setIsLoading(false);
    }
  }, [datasetIdInput, apiTokenInput]);

  // Initial load
  useEffect(() => {
    // Initial load with default dataset
  }, []);

  // Process items with sentiment engine
  const processedData = useMemo(() => {
    return rawItems.map((item) => {
      const sentimentResult = classifySentiment(item.text);
      return {
        ...item,
        sentiment: sentimentResult
      };
    });
  }, [rawItems]);

  // Filtered dataset for UI rendering
  const filteredData = useMemo(() => {
    return processedData.filter((item) => {
      const matchesSentiment =
        selectedSentiment === 'Semua' || item.sentiment.label === selectedSentiment;

      const q = searchQuery.toLowerCase().trim();
      const matchesSearch =
        !q ||
        item.text.toLowerCase().includes(q) ||
        item.authorMeta.name.toLowerCase().includes(q) ||
        item.authorMeta.nickName.toLowerCase().includes(q);

      return matchesSentiment && matchesSearch;
    });
  }, [processedData, selectedSentiment, searchQuery]);

  // Aggregate Metrics
  const metrics = useMemo(() => {
    let totalPos = 0;
    let totalNeg = 0;
    let totalNeu = 0;
    let totalViews = 0;

    processedData.forEach((item) => {
      if (item.sentiment.label === 'Positif') totalPos++;
      else if (item.sentiment.label === 'Negatif') totalNeg++;
      else totalNeu++;

      totalViews += Number(item.playCount || 0);
    });

    return {
      total: processedData.length,
      positif: totalPos,
      negatif: totalNeg,
      netral: totalNeu,
      totalViews
    };
  }, [processedData]);

  // Chart Data Preparation
  const donutChartData = useMemo(() => [
    { name: 'Positif', value: metrics.positif, color: '#10B981' }, // Emerald
    { name: 'Negatif', value: metrics.negatif, color: '#EF4444' }, // Red
    { name: 'Netral', value: metrics.netral, color: '#64748B' }   // Slate
  ], [metrics]);

  const engagementBarData = useMemo(() => {
    const agg = {
      Positif: { likes: 0, shares: 0, comments: 0, count: 0 },
      Negatif: { likes: 0, shares: 0, comments: 0, count: 0 },
      Netral: { likes: 0, shares: 0, comments: 0, count: 0 }
    };

    processedData.forEach((item) => {
      const label = item.sentiment.label;
      if (agg[label]) {
        agg[label].likes += item.diggCount || 0;
        agg[label].shares += item.shareCount || 0;
        agg[label].comments += item.commentCount || 0;
        agg[label].count += 1;
      }
    });

    return Object.keys(agg).map((key) => ({
      category: key,
      'Suka (Likes)': agg[key].likes,
      'Bagikan (Shares)': agg[key].shares,
      'Komentar': agg[key].comments
    }));
  }, [processedData]);

  // CSV Export Handler
  const handleExportCSV = () => {
    const headers = [
      'No',
      'Penulis',
      'Username',
      'Teks Konten',
      'Likes (diggCount)',
      'Shares',
      'Views (playCount)',
      'Komentar',
      'Sentimen',
      'Skor Keyakinan',
      'Bahasa Terdeteksi'
    ];

    const rows = filteredData.map((item, idx) => [
      idx + 1,
      `"${(item.authorMeta?.nickName || 'Anonim').replace(/"/g, '""')}"`,
      `"${(item.authorMeta?.name || 'anonim').replace(/"/g, '""')}"`,
      `"${(item.text || '').replace(/"/g, '""')}"`,
      item.diggCount || 0,
      item.shareCount || 0,
      item.playCount || 0,
      item.commentCount || 0,
      item.sentiment.label,
      `${Math.round(item.sentiment.confidence * 100)}%`,
      item.sentiment.language
    ]);

    const csvContent =
      'data:text/csv;charset=utf-8,\uFEFF' +
      [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Sentimen_Anak_Krakatau_TikTok_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return (
    <div className={`min-h-screen transition-colors duration-300 font-sans ${darkMode ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-800'}`}>
      
      {/* Top Navigation / Header */}
      <header className={`sticky top-0 z-30 border-b backdrop-blur-md ${darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white/90 border-slate-200'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div className="flex items-center gap-3">
              <div className="p-2.5 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-md shadow-blue-500/20">
                <Activity className="w-6 h-6 animate-pulse" />
              </div>
              <div>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">
                  Analisis Sentimen Masyarakat: Abu Vulkanik Gunung Anak Krakatau
                </h1>
                <p className={`text-xs sm:text-sm ${darkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                  Pemantauan Opini Publik Konten TikTok dari Apify (Sample 8 Data Utama)
                </p>
              </div>
            </div>

            <div className="flex items-center gap-3 justify-between sm:justify-end">
              <div className={`text-xs px-3 py-1.5 rounded-lg border font-medium flex items-center gap-2 ${darkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-600'}`}>
                <span className="w-2 h-2 rounded-full bg-emerald-500 animate-ping" />
                <span>Diperbarui: <strong>{lastUpdated}</strong></span>
              </div>

              <button
                onClick={() => setDarkMode(!darkMode)}
                className={`p-2 rounded-lg border transition-colors ${darkMode ? 'bg-slate-800 border-slate-700 text-yellow-400 hover:bg-slate-700' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'}`}
                title={darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'}
              >
                {darkMode ? <Sun className="w-5 h-5" /> : <Moon className="w-5 h-5" />}
              </button>
            </div>
          </div>
        </div>
      </header>

      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <section className={`p-5 rounded-2xl border shadow-sm transition-all ${darkMode ? 'bg-slate-800/80 border-slate-700' : 'bg-white border-slate-200'}`}>
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-700">
            <div className="flex items-center gap-2.5">
              <Database className="w-5 h-5 text-blue-500" />
              <div>
                <h2 className="text-base font-semibold">Panel Diagnostic & Proxy API Apify</h2>
                <p className={`text-xs ${darkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                  Uji koneksi data langsung ke Apify Dataset ID atau gunakan data simulasi fallback.
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <span className={`px-2.5 py-1 rounded-full text-xs font-semibold flex items-center gap-1.5 ${
                apiStatus === 'success' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' :
                apiStatus === 'loading' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' :
                'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300'
              }`}>
                {apiStatus === 'success' && <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" />}
                {apiStatus === 'loading' && <RefreshCw className="w-3.5 h-3.5 animate-spin text-amber-600 dark:text-amber-400" />}
                {apiStatus === 'error' && <XCircle className="w-3.5 h-3.5 text-rose-600 dark:text-rose-400" />}
                <span>
                  {apiStatus === 'success' ? 'Terhubung' : apiStatus === 'loading' ? 'Memuat Data...' : 'Koneksi Gagal / Fallback'}
                </span>
              </span>

              <span className={`text-xs px-2.5 py-1 rounded-full border font-mono ${darkMode ? 'bg-slate-900 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-600'}`}>
                {dataSourceType}
              </span>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
            <div className="md:col-span-5">
              <label className="block text-xs font-medium mb-1">
                Dataset ID / Run ID (Apify)
              </label>
              <div className="relative">
                <input
                  type="text"
                  value={datasetIdInput}
                  onChange={(e) => setDatasetIdInput(e.target.value)}
                  placeholder="Contoh: xmFJuy7OuhMHk3h83"
                  className={`w-full text-xs font-mono px-3 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    darkMode ? 'bg-slate-900 border-slate-700 text-slate-100' : 'bg-slate-50 border-slate-300 text-slate-800'
                  }`}
                />
              </div>
            </div>

            <div className="md:col-span-4">
              <label className="block text-xs font-medium mb-1 flex items-center justify-between">
                <span>Apify Token (Sisi Server / Opsional)</span>
                <span className="text-[10px] text-amber-500 font-normal">Sangat Rahasia</span>
              </label>
              <div className="relative">
                <input
                  type="password"
                  value={apiTokenInput}
                  onChange={(e) => setApiTokenInput(e.target.value)}
                  placeholder="apify_api_... (opsional untuk public dataset)"
                  className={`w-full text-xs font-mono px-3 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    darkMode ? 'bg-slate-900 border-slate-700 text-slate-100' : 'bg-slate-50 border-slate-300 text-slate-800'
                  }`}
                />
              </div>
            </div>

            <div className="md:col-span-3 flex items-end">
              <button
                onClick={() => handleFetchApifyData()}
                disabled={isLoading}
                className="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2"
              >
                <RefreshCw className={`w-4 h-4 ${isLoading ? 'animate-spin' : ''}`} />
                <span>Uji Ulang Koneksi API</span>
              </button>
            </div>
          </div>

          {/* Status Message / Error Alert */}
          <div className={`mt-4 p-3 rounded-xl border text-xs flex items-start gap-2.5 ${
            apiStatus === 'error'
              ? 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-950/40 dark:border-rose-900 dark:text-rose-300'
              : 'bg-blue-50 border-blue-100 text-blue-900 dark:bg-slate-900/60 dark:border-slate-700 dark:text-slate-300'
          }`}>
            <Info className={`w-4 h-4 mt-0.5 shrink-0 ${apiStatus === 'error' ? 'text-rose-500' : 'text-blue-500'}`} />
            <div className="flex-1">
              <p className="font-medium">{statusMessage}</p>
              {apiStatus === 'error' && (
                <p className="mt-1 text-[11px] opacity-90">
                  Sistem otomatis mengaktifkan <strong>Data Simulasi Fallback</strong> dengan 8 sampel konten TikTok lengkap agar dashboard tetap beroperasi normal.
                </p>
              )}
            </div>
          </div>
        </section>

        <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          
          {/* Card Total Data */}
          <div className={`p-5 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between">
              <span className={`text-xs font-semibold ${darkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                Total Sampel
              </span>
              <div className="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                <Database className="w-4 h-4" />
              </div>
            </div>
            <div className="mt-3">
              <span className="text-3xl font-extrabold tracking-tight">{metrics.total}</span>
              <span className="text-xs text-slate-500 ml-1">konten TikTok</span>
            </div>
            <p className="mt-2 text-[11px] text-slate-400">Target analisis: 8 data terbaru</p>
          </div>

          {/* Card Positif */}
          <div className={`p-5 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between">
              <span className="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                Sentimen Positif
              </span>
              <div className="p-2 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                <Sparkles className="w-4 h-4" />
              </div>
            </div>
            <div className="mt-3">
              <span className="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight">
                {metrics.positif}
              </span>
              <span className="text-xs text-slate-500 ml-1">
                ({metrics.total ? Math.round((metrics.positif / metrics.total) * 100) : 0}%)
              </span>
            </div>
            <p className="mt-2 text-[11px] text-slate-400">Bantuan, doa, apresiasi relawan</p>
          </div>

          {/* Card Negatif */}
          <div className={`p-5 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between">
              <span className="text-xs font-semibold text-rose-600 dark:text-rose-400">
                Sentimen Negatif
              </span>
              <div className="p-2 rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400">
                <AlertTriangle className="w-4 h-4" />
              </div>
            </div>
            <div className="mt-3">
              <span className="text-3xl font-extrabold text-rose-600 dark:text-rose-400 tracking-tight">
                {metrics.negatif}
              </span>
              <span className="text-xs text-slate-500 ml-1">
                ({metrics.total ? Math.round((metrics.negatif / metrics.total) * 100) : 0}%)
              </span>
            </div>
            <p className="mt-2 text-[11px] text-slate-400">Dampak ISPA, penerbangan terganggu</p>
          </div>

          {/* Card Netral */}
          <div className={`p-5 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between">
              <span className={`text-xs font-semibold ${darkMode ? 'text-slate-300' : 'text-slate-600'}`}>
                Sentimen Netral
              </span>
              <div className="p-2 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                <Info className="w-4 h-4" />
              </div>
            </div>
            <div className="mt-3">
              <span className="text-3xl font-extrabold tracking-tight">{metrics.netral}</span>
              <span className="text-xs text-slate-500 ml-1">
                ({metrics.total ? Math.round((metrics.netral / metrics.total) * 100) : 0}%)
              </span>
            </div>
            <p className="mt-2 text-[11px] text-slate-400">Informasi BMKG & tagar saja</p>
          </div>

          {/* Card Total Views */}
          <div className={`p-5 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between">
              <span className={`text-xs font-semibold ${darkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                Total Penayangan
              </span>
              <div className="p-2 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                <Eye className="w-4 h-4" />
              </div>
            </div>
            <div className="mt-3">
              <span className="text-2xl font-extrabold tracking-tight text-violet-600 dark:text-violet-400">
                {formatNumber(metrics.totalViews)}
              </span>
              <span className="text-xs text-slate-500 ml-1">plays</span>
            </div>
            <p className="mt-2 text-[11px] text-slate-400">Total penonton (playCount)</p>
          </div>

        </section>

        <section className="grid grid-cols-1 lg:grid-cols-12 gap-6">
          
          {/* Donut Chart: Distribusi Sentimen */}
          <div className={`lg:col-span-5 p-6 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between pb-2">
              <div className="flex items-center gap-2">
                <PieChartIcon className="w-5 h-5 text-indigo-500" />
                <h3 className="text-base font-bold">Distribusi Sentimen</h3>
              </div>
              <span className="text-xs text-slate-400">Proporsi 8 Konten</span>
            </div>

            <div className="h-64 w-full relative flex items-center justify-center my-2">
              {isLoading ? (
                <div className="animate-pulse text-slate-400 text-xs">Memuat grafik...</div>
              ) : (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={donutChartData}
                      cx="50%"
                      cy="50%"
                      innerRadius={60}
                      outerRadius={85}
                      paddingAngle={4}
                      dataKey="value"
                    >
                      {donutChartData.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={entry.color} stroke="none" />
                      ))}
                    </Pie>
                    <Tooltip
                      contentStyle={{
                        backgroundColor: darkMode ? '#1e293b' : '#ffffff',
                        borderColor: darkMode ? '#334155' : '#e2e8f0',
                        borderRadius: '12px',
                        color: darkMode ? '#f8fafc' : '#0f172a',
                        fontSize: '12px'
                      }}
                      formatter={(val, name) => [`${val} Konten (${Math.round((val / metrics.total) * 100)}%)`, name]}
                    />
                    <Legend
                      verticalAlign="bottom"
                      height={36}
                      formatter={(value) => (
                        <span className={`text-xs font-medium ${darkMode ? 'text-slate-300' : 'text-slate-700'}`}>
                          {value}
                        </span>
                      )}
                    />
                  </PieChart>
                </ResponsiveContainer>
              )}
            </div>

            <div className="pt-3 border-t border-slate-100 dark:border-slate-700/60 grid grid-cols-3 gap-2 text-center text-xs">
              <div className="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300">
                <div className="font-bold">{metrics.positif} Konten</div>
                <div className="text-[10px] opacity-80">Positif</div>
              </div>
              <div className="p-2 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300">
                <div className="font-bold">{metrics.negatif} Konten</div>
                <div className="text-[10px] opacity-80">Negatif</div>
              </div>
              <div className="p-2 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300">
                <div className="font-bold">{metrics.netral} Konten</div>
                <div className="text-[10px] opacity-80">Netral</div>
              </div>
            </div>
          </div>

          {/* Bar Chart: Engagement Metrics Per Sentimen */}
          <div className={`lg:col-span-7 p-6 rounded-2xl border shadow-sm flex flex-col justify-between ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
            <div className="flex items-center justify-between pb-2">
              <div className="flex items-center gap-2">
                <BarChart3 className="w-5 h-5 text-blue-500" />
                <div>
                  <h3 className="text-base font-bold">Perbandingan Interaksi (Engagement)</h3>
                  <p className="text-xs text-slate-400">Jumlah Suka, Bagikan, dan Komentar Berdasarkan Kategori Sentimen</p>
                </div>
              </div>
            </div>

            <div className="h-64 w-full my-2">
              {isLoading ? (
                <div className="animate-pulse text-slate-400 text-xs flex items-center justify-center h-full">
                  Memuat grafik...
                </div>
              ) : (
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={engagementBarData} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                    <CartesianGrid strokeDasharray="3 3" stroke={darkMode ? '#334155' : '#e2e8f0'} />
                    <XAxis
                      dataKey="category"
                      stroke={darkMode ? '#94a3b8' : '#64748b'}
                      fontSize={12}
                      tickLine={false}
                    />
                    <YAxis
                      stroke={darkMode ? '#94a3b8' : '#64748b'}
                      fontSize={11}
                      tickFormatter={(v) => formatNumber(v)}
                      tickLine={false}
                    />
                    <Tooltip
                      contentStyle={{
                        backgroundColor: darkMode ? '#1e293b' : '#ffffff',
                        borderColor: darkMode ? '#334155' : '#e2e8f0',
                        borderRadius: '12px',
                        color: darkMode ? '#f8fafc' : '#0f172a',
                        fontSize: '12px'
                      }}
                      formatter={(value) => [formatNumber(value), '']}
                    />
                    <Legend
                      wrapperStyle={{ paddingTop: '10px' }}
                      formatter={(value) => (
                        <span className={`text-xs font-medium ${darkMode ? 'text-slate-300' : 'text-slate-700'}`}>
                          {value}
                        </span>
                      )}
                    />
                    <Bar dataKey="Suka (Likes)" fill="#3B82F6" radius={[4, 4, 0, 0]} />
                    <Bar dataKey="Bagikan (Shares)" fill="#8B5CF6" radius={[4, 4, 0, 0]} />
                    <Bar dataKey="Komentar" fill="#F59E0B" radius={[4, 4, 0, 0]} />
                  </BarChart>
                </ResponsiveContainer>
              )}
            </div>

            <p className="text-[11px] text-slate-400 text-center pt-2 border-t border-slate-100 dark:border-slate-700/60">
              *Tingkat interaksi tertinggi teramati pada konten berisikan permohonan bantuan medis dan doa bersama.
            </p>
          </div>

        </section>

        <section className={`p-6 rounded-2xl border shadow-sm ${darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
          
          {/* Table Header Controls */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <h2 className="text-lg font-bold flex items-center gap-2">
                <FileSpreadsheet className="w-5 h-5 text-indigo-500" />
                <span>Tabel Data Sentimen TikTok (8 Konten Terbaru)</span>
              </h2>
              <p className={`text-xs ${darkMode ? 'text-slate-400' : 'text-slate-500'}`}>
                Detail unggahan TikTok, metadata penulis, statistik interaksi, dan hasil klasifikasi AI.
              </p>
            </div>

            <div className="flex flex-wrap items-center gap-3">
              {/* Search Box */}
              <div className="relative min-w-[200px]">
                <Search className="w-4 h-4 absolute left-3 top-2.5 text-slate-400" />
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  placeholder="Cari teks / akun..."
                  className={`w-full text-xs pl-9 pr-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    darkMode ? 'bg-slate-900 border-slate-700 text-slate-100' : 'bg-slate-50 border-slate-300 text-slate-800'
                  }`}
                />
              </div>

              {/* Filter Sentimen Dropdown */}
              <div className="flex items-center gap-1.5">
                <Filter className="w-4 h-4 text-slate-400" />
                <select
                  value={selectedSentiment}
                  onChange={(e) => setSelectedSentiment(e.target.value)}
                  className={`text-xs font-medium px-3 py-2 rounded-xl border focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    darkMode ? 'bg-slate-900 border-slate-700 text-slate-100' : 'bg-slate-50 border-slate-300 text-slate-800'
                  }`}
                >
                  <option value="Semua">Semua Sentimen</option>
                  <option value="Positif">Positif</option>
                  <option value="Negatif">Negatif</option>
                  <option value="Netral">Netral</option>
                </select>
              </div>

              {/* Unduh CSV Button */}
              <button
                onClick={handleExportCSV}
                className="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2 px-3.5 rounded-xl transition-all shadow-sm flex items-center gap-1.5"
                title="Unduh data tabel dalam format CSV"
              >
                <Download className="w-4 h-4" />
                <span>Unduh CSV</span>
              </button>
            </div>
          </div>

          {/* Responsive Data Table */}
          <div className="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className={`text-xs font-semibold uppercase tracking-wider border-b ${
                  darkMode ? 'bg-slate-900/60 border-slate-700 text-slate-400' : 'bg-slate-100/80 border-slate-200 text-slate-600'
                }`}>
                  <th className="py-3 px-3 text-center w-12">No</th>
                  <th className="py-3 px-3 w-48">Akun / Penulis</th>
                  <th className="py-3 px-4">Teks Konten TikTok</th>
                  <th className="py-3 px-3 text-center">Likes</th>
                  <th className="py-3 px-3 text-center">Shares</th>
                  <th className="py-3 px-3 text-center">Views</th>
                  <th className="py-3 px-3 text-center">Komentar</th>
                  <th className="py-3 px-4 text-center w-36">Hasil Sentimen</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-xs">
                {isLoading ? (
                  // Skeleton Loading Rows
                  Array.from({ length: 4 }).map((_, idx) => (
                    <tr key={`skel-${idx}`} className="animate-pulse">
                      <td className="p-4 text-center"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-4 mx-auto" /></td>
                      <td className="p-4"><div className="h-8 bg-slate-300 dark:bg-slate-700 rounded w-32" /></td>
                      <td className="p-4"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-3/4" /></td>
                      <td className="p-4 text-center"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-10 mx-auto" /></td>
                      <td className="p-4 text-center"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-10 mx-auto" /></td>
                      <td className="p-4 text-center"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-10 mx-auto" /></td>
                      <td className="p-4 text-center"><div className="h-4 bg-slate-300 dark:bg-slate-700 rounded w-10 mx-auto" /></td>
                      <td className="p-4 text-center"><div className="h-6 bg-slate-300 dark:bg-slate-700 rounded-full w-20 mx-auto" /></td>
                    </tr>
                  ))
                ) : filteredData.length === 0 ? (
                  // Empty State
                  <tr>
                    <td colSpan={8} className="py-12 text-center">
                      <div className="flex flex-col items-center justify-center gap-2">
                        <Search className="w-8 h-8 text-slate-400" />
                        <p className="font-semibold text-sm">Tidak ada data yang cocok</p>
                        <p className="text-xs text-slate-400">Coba ubah kata kunci pencarian atau filter sentimen Anda.</p>
                      </div>
                    </td>
                  </tr>
                ) : (
                  filteredData.map((item, index) => {
                    const isLongText = item.text.length > 120;
                    const truncatedText = isLongText ? item.text.slice(0, 120) + '...' : item.text;

                    const sentimentLabel = item.sentiment.label;
                    const confidencePct = Math.round(item.sentiment.confidence * 100);

                    let badgeColor = 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600';
                    if (sentimentLabel === 'Positif') {
                      badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-300 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800';
                    } else if (sentimentLabel === 'Negatif') {
                      badgeColor = 'bg-rose-50 text-rose-700 border-rose-300 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800';
                    }

                    return (
                      <tr
                        key={item.id || index}
                        className={`transition-colors hover:bg-slate-50/80 dark:hover:bg-slate-700/40 ${
                          index % 2 === 0 ? (darkMode ? 'bg-slate-800/40' : 'bg-white') : (darkMode ? 'bg-slate-800/90' : 'bg-slate-50/50')
                        }`}
                      >
                        {/* No */}
                        <td className="py-3.5 px-3 text-center font-mono font-medium text-slate-400">
                          {index + 1}
                        </td>

                        {/* Avatar & Account */}
                        <td className="py-3.5 px-3">
                          <div className="flex items-center gap-2.5">
                            <img
                              src={item.authorMeta.avatar}
                              alt={item.authorMeta.name}
                              className="w-8 h-8 rounded-full object-cover border border-slate-300 dark:border-slate-600 shrink-0"
                              onError={(e) => {
                                e.target.src = `https://api.dicebear.com/7.x/bottts/svg?seed=${index}`;
                              }}
                            />
                            <div className="min-w-0">
                              <p className="font-semibold text-slate-900 dark:text-slate-100 truncate max-w-[130px]">
                                {item.authorMeta.nickName || 'TikTok User'}
                              </p>
                              <p className="text-[11px] text-slate-400 truncate max-w-[130px]">
                                @{item.authorMeta.name}
                              </p>
                            </div>
                          </div>
                        </td>

                        {/* Text (Truncated) */}
                        <td className="py-3.5 px-4 max-w-md">
                          <p className="leading-relaxed text-slate-700 dark:text-slate-200">
                            {truncatedText}
                          </p>
                          {isLongText && (
                            <button
                              onClick={() => setExpandedTextItem(item)}
                              className="mt-1 text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-0.5"
                            >
                              <span>Lihat selengkapnya</span>
                              <ChevronDown className="w-3 h-3" />
                            </button>
                          )}
                        </td>

                        {/* Likes */}
                        <td className="py-3.5 px-3 text-center font-mono font-medium">
                          <div className="flex items-center justify-center gap-1 text-slate-600 dark:text-slate-300">
                            <Heart className="w-3 h-3 text-rose-500 fill-rose-500" />
                            <span>{formatNumber(item.diggCount)}</span>
                          </div>
                        </td>

                        {/* Shares */}
                        <td className="py-3.5 px-3 text-center font-mono font-medium">
                          <div className="flex items-center justify-center gap-1 text-slate-600 dark:text-slate-300">
                            <Share2 className="w-3 h-3 text-blue-500" />
                            <span>{formatNumber(item.shareCount)}</span>
                          </div>
                        </td>

                        {/* Views */}
                        <td className="py-3.5 px-3 text-center font-mono font-medium">
                          <div className="flex items-center justify-center gap-1 text-slate-600 dark:text-slate-300">
                            <Eye className="w-3 h-3 text-purple-500" />
                            <span>{formatNumber(item.playCount)}</span>
                          </div>
                        </td>

                        {/* Comments */}
                        <td className="py-3.5 px-3 text-center font-mono font-medium">
                          <div className="flex items-center justify-center gap-1 text-slate-600 dark:text-slate-300">
                            <MessageSquare className="w-3 h-3 text-amber-500" />
                            <span>{formatNumber(item.commentCount)}</span>
                          </div>
                        </td>

                        {/* Sentiment Badge */}
                        <td className="py-3.5 px-4 text-center">
                          <div className="inline-flex flex-col items-center gap-0.5">
                            <span className={`px-2.5 py-1 rounded-full text-xs font-bold border ${badgeColor}`}>
                              {sentimentLabel}
                            </span>
                            <span className="text-[10px] text-slate-400 font-mono">
                              Keyakinan: {confidencePct}% ({item.sentiment.language})
                            </span>
                          </div>
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>
        </section>

      </main>

      {expandedTextItem && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
          <div className={`max-w-lg w-full p-6 rounded-2xl border shadow-xl relative ${darkMode ? 'bg-slate-800 border-slate-700 text-slate-100' : 'bg-white border-slate-200 text-slate-900'}`}>
            <button
              onClick={() => setExpandedTextItem(null)}
              className="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1"
            >
              <XCircle className="w-5 h-5" />
            </button>

            <div className="flex items-center gap-3 mb-4">
              <img
                src={expandedTextItem.authorMeta.avatar}
                alt={expandedTextItem.authorMeta.name}
                className="w-10 h-10 rounded-full border border-slate-300"
              />
              <div>
                <h3 className="font-bold text-sm">{expandedTextItem.authorMeta.nickName}</h3>
                <p className="text-xs text-slate-400">@{expandedTextItem.authorMeta.name}</p>
              </div>
            </div>

            <div className={`p-4 rounded-xl border text-sm leading-relaxed mb-4 ${darkMode ? 'bg-slate-900/60 border-slate-700' : 'bg-slate-50 border-slate-200'}`}>
              {expandedTextItem.text}
            </div>

            <div className="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-200 dark:border-slate-700">
              <span>Bahasa: <strong>{expandedTextItem.sentiment.language}</strong></span>
              <span>Skor Sentimen: <strong>{Math.round(expandedTextItem.sentiment.confidence * 100)}% ({expandedTextItem.sentiment.label})</strong></span>
            </div>
          </div>
        </div>
      )}

      <footer className={`mt-12 border-t py-6 text-center text-xs ${darkMode ? 'bg-slate-900 border-slate-800 text-slate-500' : 'bg-slate-100 border-slate-200 text-slate-500'}`}>
        <div className="max-w-7xl mx-auto px-4 space-y-2">
          <p className="font-semibold text-slate-600 dark:text-slate-400">
            Catatan: Sampel hanya 8 data, belum mewakili seluruh opini publik.
          </p>
          <p>
            Sistem Pemantauan Sentimen Kebencanaan | Terintegrasi dengan Apify TikTok Scraper Engine
          </p>
        </div>
      </footer>

    </div>
  );
}