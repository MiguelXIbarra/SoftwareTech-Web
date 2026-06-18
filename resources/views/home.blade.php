@extends('layouts.app')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    /* Estilos del Navbar Fijo y Drawer Lateral */
    .tech-navbar {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        background: rgba(3, 7, 18, 0.7) !important;
        height: 75px;
        display: flex;
        align-items: center;
        z-index: 1050;
    }

    .brand-logo-glow {
        font-weight: 800;
        letter-spacing: 2px;
        color: #ffffff !important;
        text-shadow: 0 0 10px rgba(6, 182, 212, 0.4);
    }

    .toggle-icon-glow {
        color: #06b6d4;
        text-shadow: 0 0 8px rgba(6, 182, 212, 0.6);
    }

    .tech-nav-link {
        font-family: monospace;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7) !important;
        letter-spacing: 2px;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 8px 0;
    }

    .tech-nav-link:hover {
        color: #06b6d4 !important;
        text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }

    .btn-portal-nav {
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.8rem;
        font-family: monospace;
        letter-spacing: 1.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(138, 43, 226, 0.08);
        border: 1px solid rgba(138, 43, 226, 0.3);
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100%;
    }

    @media (min-width: 992px) {
        .btn-portal-nav {
            width: auto;
        }
    }

    .btn-portal-nav:hover {
        border-color: #8a2be2;
        background: rgba(138, 43, 226, 0.2);
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.4);
        transform: translateY(-1px);
    }

    @media (max-width: 991.98px) {
        .tech-drawer {
            background: rgba(3, 7, 18, 0.95) !important;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-left: 1px solid rgba(255, 255, 255, 0.08) !important;
            width: 280px !important;
        }

        .navbar-nav {
            margin-top: 1.5rem;
        }

        .tech-nav-link {
            font-size: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            padding: 12px 0;
        }
    }

    /* Contenedor Principal */
    .main-terminal {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        margin-top: -75px;
        overflow-x: hidden;
        color: #ffffff;
        position: relative;
    }

    .main-terminal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 80% 20%, rgba(138, 43, 226, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 20% 60%, rgba(0, 123, 255, 0.12) 0%, transparent 50%),
            radial-gradient(circle at 50% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 45%);
        z-index: 1;
        pointer-events: none;
    }

    .tech-hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 140px 20px 80px 20px;
        position: relative;
        z-index: 2;
    }

    @media (min-width: 992px) {
        .tech-hero-section {
            padding: 160px 0 100px 0;
        }
    }

    .hero-content {
        position: relative;
        z-index: 5;
    }

    .hero-headline-wrap {
        min-height: 160px;
    }

    .hero-headline {
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -1.5px;
        color: #ffffff;
        margin-bottom: 30px;
    }

    .hero-headline span.headline-main-row {
        display: block;
        margin-bottom: 12px;
    }

    .hero-headline .dna-gradient-text {
        background: linear-gradient(135deg, #007bff, #06b6d4, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        min-height: 1.3em;
    }

    .typed-cursor {
        color: #06b6d4;
        font-weight: 300;
        animation: blink 0.7s infinite;
        margin-left: 4px;
    }

    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }

    .hero-subtext {
        font-size: 1.15rem;
        color: #9ca3af;
        font-weight: 400;
        letter-spacing: -0.2px;
        max-width: 620px;
        margin-bottom: 45px;
        line-height: 1.6;
    }

    .btn-ohio-text {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 16px 32px;
        border-radius: 14px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        justify-content: center;
    }

    @media (min-width: 576px) {
        .btn-ohio-text {
            width: auto;
            justify-content: flex-start;
        }
    }

    .btn-ohio-text:hover {
        border-color: rgba(6, 182, 212, 0.4);
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.2);
        transform: translateY(-2px);
    }

    .btn-ohio-text i {
        color: #06b6d4;
        transition: transform 0.3s ease;
    }

    .btn-ohio-text:hover i {
        transform: translateX(6px);
        color: #8a2be2;
    }

    .logo-animation-stage {
        position: relative;
        width: 100%;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible;
    }

    .circuit-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(6, 182, 212, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .ring-1 { width: 260px; height: 260px; border: 1px dashed rgba(6, 182, 212, 0.2); animation: rotateClockwise 25s linear infinite; }
    .ring-2 { width: 360px; height: 360px; border: 1px dotted rgba(138, 43, 226, 0.25); animation: rotateCounterClockwise 35s linear infinite; }
    .ring-3 { width: 460px; height: 460px; border: 1px solid rgba(255, 255, 255, 0.03); animation: rotateClockwise 50s linear infinite; }

    .quantum-node {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
        cursor: pointer;
        pointer-events: auto;
        z-index: 15;
    }

    .quantum-node::before {
        content: '';
        position: absolute;
        width: 100%; height: 100%;
        border-radius: 50%;
        background: inherit;
        animation: luxPulse 2s infinite ease-in-out;
    }

    @keyframes luxPulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }

    .node-cyan { background: #06b6d4; box-shadow: 0 0 12px #06b6d4, 0 0 25px rgba(6, 182, 212, 0.6); }
    .node-purple { background: #8a2be2; box-shadow: 0 0 12px #8a2be2, 0 0 25px rgba(138, 43, 226, 0.6); }
    .node-blue { background: #007bff; box-shadow: 0 0 12px #007bff, 0 0 25px rgba(0, 123, 255, 0.6); }

    .ring-1 .quantum-node:nth-child(1) { top: 0; left: 50%; transform: translate(-50%, -50%); }
    .ring-1 .quantum-node:nth-child(2) { bottom: 0; left: 50%; transform: translate(-50%, 50%); }
    .ring-2 .quantum-node:nth-child(1) { top: 50%; left: 0; transform: translate(-50%, -50%); }
    .ring-2 .quantum-node:nth-child(2) { top: 50%; right: 0; transform: translate(50%, -50%); }
    .ring-3 .quantum-node:nth-child(1) { top: 14.6%; left: 14.6%; transform: translate(-50%, -50%); }
    .ring-3 .quantum-node:nth-child(2) { bottom: 14.6%; right: 14.6%; transform: translate(50%, 50%); }

    .energy-beam {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.05) 0%, transparent 70%);
        animation: beamPulse 3s ease-in-out infinite alternate;
        z-index: 1;
    }

    @keyframes rotateClockwise { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes rotateCounterClockwise { 0% { transform: rotate(360deg); } 100% { transform: rotate(0deg); } }
    @keyframes beamPulse { 0% { transform: scale(0.85); opacity: 0.3; } 100% { transform: scale(1.1); opacity: 0.8; } }

    .lux-floating-label {
        position: absolute;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 6px 12px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.7rem;
        color: #e5e7eb;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
        white-space: nowrap;
        z-index: 20;
    }

    .quantum-node:hover .lux-floating-label { opacity: 1; transform: translateY(-35px); }

    .section-wrapper {
        padding: 80px 20px;
        position: relative;
        z-index: 3;
    }

    @media (min-width: 768px) {
        .section-wrapper {
            padding: 120px 0;
        }
    }

    .ohio-headline-label {
        font-family: monospace;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: #06b6d4;
        margin-bottom: 20px;
        display: block;
        font-weight: 700;
    }

    .auth-banner-panel {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        border-left: 5px solid #8a2be2 !important;
        padding: 25px 35px !important;
        border-radius: 16px !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4) !important;
    }

    .subl-banner-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        height: 430px;
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .subl-banner-card .img-container {
        width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 1;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .subl-banner-card .img-container img {
        width: 100%; height: 100%; object-fit: cover;
        filter: brightness(0.15) grayscale(0.2); transition: all 0.5s ease;
    }

    .subl-banner-card:hover { border-color: rgba(6, 182, 212, 0.3); box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 40px rgba(6, 182, 212, 0.05); transform: translateY(-5px); }
    .subl-banner-card:hover .img-container { transform: scale(1.04); }
    .subl-banner-card:hover .img-container img { filter: brightness(0.25) saturate(120%); }

    .card-overlay-details { position: absolute; bottom: 0; left: 0; width: 100%; padding: 40px 25px; z-index: 3; background: linear-gradient(to top, rgba(3, 7, 18, 0.95) 45%, transparent 100%); }

    @media (min-width: 576px) {
        .card-overlay-details { padding: 40px 35px; }
    }

    .card-label-mono { font-family: monospace; font-size: 0.75rem; color: #06b6d4; letter-spacing: 2px; display: block; margin-bottom: 8px; font-weight: 600; }
    .card-headline-title { font-size: 1.35rem; font-weight: 700; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 12px; }
    .card-paragraph-desc { color: #9ca3af !important; font-size: 0.9rem; line-height: 1.6; margin-bottom: 0; }

    .metrics-row { border-top: 1px solid rgba(255, 255, 255, 0.04); border-bottom: 1px solid rgba(255, 255, 255, 0.04); padding: 40px 10px; margin: 20px 0 40px 0; background: rgba(255, 255, 255, 0.01); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }

    @media (min-width: 768px) {
        .metrics-row { padding: 55px 0; margin: 20px 0 60px 0; }
    }

    .metric-item h4 { font-size: 2rem; font-weight: 800; color: #ffffff; margin-bottom: 6px; letter-spacing: -1.5px; background: linear-gradient(to bottom, #ffffff, #9ca3af); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    @media (min-width: 768px) {
        .metric-item h4 { font-size: 2.6rem; }
    }

    .metric-item p { font-family: monospace; font-size: 0.7rem; color: #6b7280; text-transform: uppercase; margin-bottom: 0; letter-spacing: 1px; }

    @media (min-width: 768px) {
        .metric-item p { font-size: 0.75rem; letter-spacing: 2px; }
    }

    .pipeline-container {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important;
        border-radius: 32px; padding: 30px 20px; position: relative;
    }

    @media (min-width: 768px) {
        .pipeline-container { padding: 60px; }
    }

    .pipeline-progress-bar { height: 2px; width: 100%; background: rgba(255, 255, 255, 0.04); position: relative; margin: 40px 0; border-radius: 2px; overflow: hidden; }
    .pipeline-progress-fill { height: 100%; width: 75%; background: linear-gradient(90deg, #007bff, #06b6d4, #8a2be2); animation: activeCore 3s ease-in-out infinite alternate; }
    @keyframes activeCore { 0% { opacity: 0.6; width: 45%; } 100% { opacity: 1; width: 80%; } }

    .pipeline-step-box { padding: 25px; background: rgba(255, 255, 255, 0.01) !important; border: 1px solid rgba(255, 255, 255, 0.05) !important; backdrop-filter: blur(12px) !important; -webkit-backdrop-filter: blur(12px) !important; border-radius: 16px; transition: all 0.3s ease; }
    .pipeline-step-box:hover { border-color: rgba(6, 182, 212, 0.2); background: rgba(255, 255, 255, 0.03); }
    .pipeline-step-box h4 { font-size: 1.1rem; font-weight: 700; color: #ffffff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .pipeline-step-box h4 span { font-family: monospace; font-size: 0.8rem; color: #06b6d4; background: rgba(6, 182, 212, 0.06); padding: 2px 8px; border-radius: 4px; }

    .showcase-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 50px; }
    .showcase-card { background: rgba(255, 255, 255, 0.02) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; border-top-color: rgba(255, 255, 255, 0.15) !important; border-left-color: rgba(255, 255, 255, 0.15) !important; backdrop-filter: blur(24px) saturate(160%) !important; -webkit-backdrop-filter: blur(24px) saturate(160%) !important; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important; border-radius: 24px; overflow: hidden; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .showcase-card:hover { transform: translateY(-6px); border-color: rgba(6, 182, 212, 0.3); box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 50px rgba(138, 43, 226, 0.05); }
    .showcase-img-wrap { height: 240px; width: 100%; overflow: hidden; position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.04); background: #030712; }
    .showcase-img-wrap img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.65); transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .showcase-card:hover .showcase-img-wrap img { transform: scale(1.04); filter: brightness(0.8); }
    .showcase-body { padding: 30px 20px; }

    @media (min-width: 576px) {
        .showcase-body { padding: 35px; }
    }

    .showcase-tag { font-family: monospace; font-size: 0.75rem; color: #06b6d4; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; font-weight: 600; }
    .showcase-title { font-size: 1.3rem; font-weight: 700; color: #ffffff; margin-bottom: 14px; }
    .showcase-desc { font-size: 0.9rem; color: #9ca3af; line-height: 1.6; margin-bottom: 0; }

    .form-glass {
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        font-weight: 500 !important;
        padding: 14px 18px !important;
        transition: all 0.3s ease !important;
    }
    .form-glass::placeholder {
        color: rgba(255, 255, 255, 0.55) !important;
        font-weight: 400 !important;
    }
    .form-glass:focus {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: #06b6d4 !important;
        color: #ffffff !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.35) !important;
    }
    textarea.form-glass {
        color: #ffffff !important;
    }

    .main-footer {
        background: #000000 !important;
        border-top: 1px solid transparent !important;
        border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;
        width: 100% !important;
        padding: 25px 30px !important;
        margin-top: 0 !important;
        text-align: center !important;
        color: rgba(200, 200, 200, 0.8) !important;
        font-size: 0.85rem !important;
        letter-spacing: 1px;
    }
    .main-footer strong,
    .main-footer a {
        color: #f8f9fa !important;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 800 !important;
        text-decoration: none !important;
    }
    .main-footer a {
        color: #00d4ff !important;
    }

    .reveal {
        position: relative;
        transform: translateY(35px);
        opacity: 0;
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .reveal.active {
        transform: translateY(0);
        opacity: 1;
    }

    .tech-showcase-section {
        padding: 60px 20px;
    }

    @media (min-width: 768px) {
        .tech-showcase-section {
            padding: 120px 0;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top px-3 px-md-5 tech-navbar">
    <div class="container-fluid px-0">
        <a class="navbar-brand fw-800 tracking-wide" href="#">
            <span class="brand-logo-glow">SOFTWARE TECH</span>
        </a>

        <button class="navbar-toggler border-0 p-2 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <i class="fas fa-bars fa-lg toggle-icon-glow"></i>
        </button>

        <div class="offcanvas offcanvas-end tech-drawer" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom border-dark-subtle d-lg-none">
                <h5 class="offcanvas-title fw-800 brand-logo-glow" id="offcanvasNavbarLabel">SOFTWARE TECH</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-4 p-lg-0">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-0 gap-3 gap-lg-4 align-items-lg-center">
                    <li class="nav-item" data-bs-dismiss="offcanvas">
                        <a class="nav-link tech-nav-link" href="#servicios">SERVICIOS</a>
                    </li>
                    <li class="nav-item" data-bs-dismiss="offcanvas">
                        <a class="nav-link tech-nav-link" href="#tecnologia">TECNOLOGÍA</a>
                    </li>
                    <li class="nav-item" data-bs-dismiss="offcanvas">
                        <a class="nav-link tech-nav-link" href="#proceso">PROCESO</a>
                    </li>
                    <li class="nav-item" data-bs-dismiss="offcanvas">
                        <a class="nav-link tech-nav-link" href="#contacto">CONTACTO</a>
                    </li>
                    <li class="nav-item mt-3 mt-lg-0" data-bs-dismiss="offcanvas">
                        <a class="btn-portal-nav" href="#">PORTAL CLIENTES</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="main-terminal">

    <section class="tech-hero-section container px-4 px-md-0">
        <div class="row align-items-center w-100 g-5 mx-0">
            <div class="col-12 col-lg-6 col-xl-5 px-0">
                <div class="hero-content">
                    <div class="hero-headline-wrap">
                        <h1 class="hero-headline">
                            <span class="headline-main-row">Ingeniería avanzada enfocada en:</span>
                            <span class="dna-gradient-text" id="typed-target">arquitecturas web.</span><span class="typed-cursor">|</span>
                        </h1>
                    </div>
                    <p class="hero-subtext">
                        Diseñamos sistemas modulares de alto rendimiento e infraestructura blindada a la medida para la automatización operativa de tu empresa.
                    </p>
                    <a href="#contacto" class="btn-ohio-text">
                        <span>Agendar Diagnóstico Gratuito</span>
                        <i class="fas fa-calendar-alt"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-7 d-none d-lg-block">
                <div class="logo-animation-stage">
                    <div class="energy-beam"></div>

                    <div class="circuit-ring ring-1">
                        <div class="quantum-node node-blue">
                            <div class="lux-floating-label">Laravel Full-Stack Engine</div>
                        </div>
                        <div class="quantum-node node-cyan">
                            <div class="lux-floating-label">Data Pipeline Core</div>
                        </div>
                    </div>

                    <div class="circuit-ring ring-2">
                        <div class="quantum-node node-purple">
                            <div class="lux-floating-label">SAST Shield Active</div>
                        </div>
                        <div class="quantum-node node-cyan">
                            <div class="lux-floating-label">Cloud Cluster Node</div>
                        </div>
                    </div>

                    <div class="circuit-ring ring-3">
                        <div class="quantum-node node-blue">
                            <div class="lux-floating-label">Automation Core</div>
                        </div>
                        <div class="quantum-node node-purple">
                            <div class="lux-floating-label">Cripto Security Panel</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
    <div class="container my-4 reveal px-4 px-md-0">
        <div class="auth-banner-panel d-flex align-items-center justify-content-between">
            <div>
                <span style="font-family: monospace !important; font-size: 0.95rem !important; color: #a78bfa !important; font-weight: 700 !important; letter-spacing: 1px !important; display: block !important; margin-bottom: 6px !important;">NÚCLEO INICIADO // {{ Auth::user()->name }}</span>
                <p style="color: #ffffff !important; font-size: 0.9rem !important; margin-bottom: 0 !important; font-weight: 400 !important; opacity: 1 !important; text-shadow: 0 1px 2px rgba(0,0,0,0.8) !important;">Portal administrativo activo. Los módulos internos de control y gestión se encuentran enlazados de forma transparente.</p>
            </div>
            <div style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 12px #06b6d4;"></div>
        </div>
    </div>
    @endauth

    <section class="section-wrapper container reveal" id="servicios">
        <span class="ohio-headline-label">Capacidades</span>
        <h2 class="text-white fw-800 mb-5" style="font-size: 2.2rem; letter-spacing: -1px;">Soluciones de software corporativo.</h2>

        <div class="row g-4 mx-0">
            @php
            $servicios = [
                ['label' => 'SISTEMAS SAAS', 'title' => 'Plataformas Web', 'desc' => 'Desarrollo de aplicaciones modulares completas full-stack estructuradas con la robustez lógica del framework Laravel.', 'img' => 'photo-1451187580459-43490279c0fa'],
                ['label' => 'EXPERTISE MÓVIL', 'title' => 'Aplicaciones Nativas', 'desc' => 'Ecosistemas móviles fluidos con persistencia segura de información y alto rendimiento de consumo cloud.', 'img' => 'photo-1550751827-4bd374c3f58b'],
                ['label' => 'CIBERSEGURIDAD', 'title' => 'Auditorías SAST', 'desc' => 'Inspección y análisis estático automatizado de código para neutralizar vulnerabilidades críticas antes del despliegue real.', 'img' => 'photo-1518770660439-4636190af475'],
                ['label' => 'AUTOMATIZACIÓN', 'title' => 'Sistemas Inteligentes', 'desc' => 'Modelos analíticos de Inteligencia Artificial para el procesamiento lógico y optimización de flujos de datos.', 'img' => 'photo-1555066931-4365d14bab8c']
            ];
            @endphp

            @foreach($servicios as $s)
            <div class="col-12 col-md-6 col-lg-3 px-0 px-md-2">
                <div class="subl-banner-card">
                    <div class="img-container">
                        <img src="https://images.unsplash.com/{{$s['img']}}?w=600" alt="Software Tech Solution">
                    </div>
                    <div class="card-overlay-details">
                        <span class="card-label-mono">{{$s['label']}}</span>
                        <h3 class="card-headline-title">{{$s['title']}}</h3>
                        <p class="card-paragraph-desc">{{$s['desc']}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <div class="row metrics-row text-center g-4 mx-0 reveal">
        <div class="col-6 col-md-3 metric-item">
            <h4>99.98%</h4>
            <p>Uptime de Servidores</p>
        </div>
        <div class="col-6 col-md-3 metric-item">
            <h4>&lt;45ms</h4>
            <p>Tiempo de Respuesta</p>
        </div>
        <div class="col-6 col-md-3 metric-item">
            <h4>100%</h4>
            <p>Cobertura SAST Segura</p>
        </div>
        <div class="col-6 col-md-3 metric-item">
            <h4>Zero-Fail</h4>
            <p>Estructura de Datos</p>
        </div>
    </div>

    <section id="tecnologia" class="tech-showcase-section container reveal">
        <span class="ohio-headline-label">Showcase</span>
        <h2 class="text-white fw-800" style="font-size: 2.2rem; letter-spacing: -1px;">Módulos e Infraestructura Desarrollada.</h2>

        <div class="showcase-grid">
            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Oraculo_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech IA Oráculo">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Oráculo</span>
                    <h3 class="showcase-title">Oráculo Chronos Engine</h3>
                    <p class="showcase-desc">Agente cognitivo avanzado que automatiza flujos de depuración y optimización sintáctica. Analiza de manera predictiva el código fuente para erradicar errores de compilación y fallas de seguridad lógicas antes del despliegue productivo.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Guardian_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech Guardián">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Guardián</span>
                    <h3 class="showcase-title">Guardian Code-Shield</h3>
                    <p class="showcase-desc">Núcleo perimetral dedicado al blindaje criptográfico de metadatos y bases de datos corporativas. Neutraliza virus, malware y accesos no autorizados, garantizando la máxima integridad y persistencia segura de la información de los clientes.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Nexus_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech Nexus">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Nexus</span>
                    <h3 class="showcase-title">Nexus Blueprint Studio</h3>
                    <p class="showcase-desc">Motor automatizado de arquitectura que compila plataformas web, aplicaciones y APIs en minutos mediante lenguaje natural. Elimina la barrera técnica para creadores digitales con despliegues instantáneos y acelera la producción en entornos corporativos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="proceso" class="container reveal section-wrapper">
        <div class="pipeline-container">
            <div class="pipeline-header">
                <span class="ohio-headline-label">Metodología de Ingeniería</span>
                <h3 class="pipeline-title text-white">Fases de despliegue estratégico.</h3>
            </div>

            <div class="pipeline-progress-bar d-none d-md-block">
                <div class="pipeline-progress-fill"></div>
            </div>

            <div class="row g-4 mt-md-2 mx-0">
                <div class="col-12 col-md-4 px-0 px-md-2">
                    <div class="pipeline-step-box">
                        <h4><span>01</span> Diagnóstico y Arquitectura</h4>
                        <p>Evaluamos la infraestructura actual de tu empresa y diseñamos la lógica del sistema sin alterar tus operaciones activas.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 px-0 px-md-2">
                    <div class="pipeline-step-box">
                        <h4><span>02</span> Desarrollo y Pruebas Simuladas</h4>
                        <p>Construimos los módulos en ambientes aislados y realizamos análisis estáticos de código para garantizar cero vulnerabilidades.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 px-0 px-md-2">
                    <div class="pipeline-step-box">
                        <h4><span>03</span> Despliegue Productivo</h4>
                        <p>Lanzamiento seguro a producción con soporte continuo, garantizando cero caídas en tu servicio.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="container section-wrapper reveal" style="padding: 80px 0;">
        <div class="row justify-content-center text-center mb-5 mx-0">
            <div class="col-12 col-md-10 col-lg-8 px-4">
                <span class="ohio-headline-label">Contacto Interno</span>
                <h2 class="text-white fw-800" style="font-size: 2.5rem; letter-spacing: -1px;">¿Listo para escalar la infraestructura de tu negocio?</h2>
                <p style="color: #9ca3af !important; margin-top: 1rem;">Solicita un diagnóstico de arquitectura gratuito y descubre cómo optimizar los activos digitales de tu corporación.</p>
            </div>
        </div>
        <div class="row justify-content-center mx-0">
            <div class="col-12 col-md-10 col-lg-7 px-4 px-md-0">
                <div class="pipeline-container p-4 p-sm-5">
                    <form onsubmit="mandarWhatsApp(event)">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" id="form-empresa" class="form-control form-glass" placeholder="Nombre Corporativo" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" id="form-correo" class="form-control form-glass" placeholder="Correo Electrónico de Enlace" required>
                            </div>
                            <div class="col-12">
                                <input type="text" id="form-asunto" class="form-control form-glass" placeholder="Asunto / Módulo de Requerimiento">
                            </div>
                            <div class="col-12">
                                <textarea id="form-mensaje" class="form-control form-glass" rows="4" placeholder="Describe brevemente las necesidades de tu ecosistema tecnológico..." required></textarea>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn-ohio-text border-0 w-100 justify-content-center">
                                    <span>Solicitar Consultoría Técnica</span>
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                    function mandarWhatsApp(event) {
                        event.preventDefault();
                        const empresa = document.getElementById('form-empresa').value;
                        const correo = document.getElementById('form-correo').value;
                        const asunto = document.getElementById('form-asunto').value;
                        const mensaje = document.getElementById('form-mensaje').value;

                        const numero = "523328395366";
                        const texto = `Hola, me interesa una consultoría técnica.%0A%0A*Empresa:* ${empresa}%0A*Correo:* ${correo}%0A*Asunto:* ${asunto}%0A*Detalles:* ${mensaje}`;

                        window.open(`https://wa.me/${numero}?text=${texto}`, '_blank');
                    }
                    </script>
                </div>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} <a href="#">SOFTWARE TECH</a> | INNOVATION LAB | V4.5</strong>
    </footer>
</div>

<script>
    // Lógica del Texto Animado (Efecto Máquina de Escribir)
    document.addEventListener("DOMContentLoaded", function() {
        const target = document.getElementById("typed-target");
        const strings = [
            "arquitecturas web.",
            "ciberseguridad SAST.",
            "aplicaciones móviles.",
            "automatizar procesos.",
            "optimizar datos."
        ];
        let stringIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentString = strings[stringIndex];
            if (isDeleting) {
                target.textContent = currentString.substring(0, charIndex - 1);
                charIndex--;
            } else {
                target.textContent = currentString.substring(0, charIndex + 1);
                charIndex++;
            }

            let typeSpeed = isDeleting ? 35 : 75;

            if (!isDeleting && charIndex === currentString.length) {
                typeSpeed = 2200;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                stringIndex = (stringIndex + 1) % strings.length;
                typeSpeed = 200;
            }

            setTimeout(type, typeSpeed);
        }
        setTimeout(type, 400);
    });

    // Animaciones Infinitas controladas por el Scroll (Subir y Bajar)
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementBottom = reveals[i].getBoundingClientRect().bottom;
            var elementVisible = 80;

            if (elementTop < windowHeight - elementVisible && elementBottom > elementVisible) {
                reveals[i].classList.add("active");
            } else {
                reveals[i].classList.remove("active");
            }
        }
    }
    window.addEventListener("scroll", reveal);
    window.addEventListener("load", reveal);
</script>
@endsection
