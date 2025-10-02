<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Modern Library Logo: Stack of Books with Gradient -->
    <defs>
        <linearGradient id="bookGradient1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
        </linearGradient>
        <linearGradient id="bookGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#8b5cf6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
        </linearGradient>
        <linearGradient id="bookGradient3" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#ec4899;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1" />
        </linearGradient>
    </defs>
    
    <!-- Book 1 (Bottom) -->
    <rect x="40" y="130" width="120" height="25" rx="3" fill="url(#bookGradient1)" />
    <rect x="40" y="130" width="8" height="25" rx="2" fill="#4338ca" opacity="0.8" />
    <line x1="50" y1="135" x2="150" y2="135" stroke="white" stroke-width="1" opacity="0.3" />
    <line x1="50" y1="145" x2="130" y2="145" stroke="white" stroke-width="1" opacity="0.3" />
    
    <!-- Book 2 (Middle) -->
    <rect x="35" y="100" width="130" height="25" rx="3" fill="url(#bookGradient2)" />
    <rect x="35" y="100" width="8" height="25" rx="2" fill="#7c3aed" opacity="0.8" />
    <line x1="45" y1="105" x2="155" y2="105" stroke="white" stroke-width="1" opacity="0.3" />
    <line x1="45" y1="115" x2="140" y2="115" stroke="white" stroke-width="1" opacity="0.3" />
    
    <!-- Book 3 (Top) -->
    <rect x="50" y="70" width="110" height="25" rx="3" fill="url(#bookGradient3)" />
    <rect x="50" y="70" width="8" height="25" rx="2" fill="#db2777" opacity="0.8" />
    <line x1="60" y1="75" x2="150" y2="75" stroke="white" stroke-width="1" opacity="0.3" />
    <line x1="60" y1="85" x2="135" y2="85" stroke="white" stroke-width="1" opacity="0.3" />
    
    <!-- Open Book on Top -->
    <g transform="translate(70, 30)">
        <!-- Left page -->
        <path d="M 0 15 Q 0 5 10 0 L 30 0 L 30 30 L 10 30 Q 0 25 0 15 Z" fill="#6366f1" opacity="0.9" />
        <line x1="5" y1="10" x2="25" y2="10" stroke="white" stroke-width="0.5" opacity="0.4" />
        <line x1="5" y1="15" x2="25" y2="15" stroke="white" stroke-width="0.5" opacity="0.4" />
        <line x1="5" y1="20" x2="25" y2="20" stroke="white" stroke-width="0.5" opacity="0.4" />
        
        <!-- Right page -->
        <path d="M 30 0 L 50 0 Q 60 5 60 15 Q 60 25 50 30 L 30 30 Z" fill="#8b5cf6" opacity="0.9" />
        <line x1="35" y1="10" x2="55" y2="10" stroke="white" stroke-width="0.5" opacity="0.4" />
        <line x1="35" y1="15" x2="55" y2="15" stroke="white" stroke-width="0.5" opacity="0.4" />
        <line x1="35" y1="20" x2="55" y2="20" stroke="white" stroke-width="0.5" opacity="0.4" />
    </g>
</svg>
