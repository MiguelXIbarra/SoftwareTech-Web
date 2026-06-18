@extends('layouts.app')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

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
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 80% 20%, rgba(138, 43, 226, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 20% 60%, rgba(0, 123, 255, 0.12) 0%, transparent 50%),
                    radial-gradient(circle at 50% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 45%);
        z-index: 1;
        pointer-events: none;
    }

    .navbar {
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background: rgba(3, 7, 18, 0.4) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
    }

    .tech-hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 160px 0 100px 0;
        position: relative;
        z-index: 2;
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

    .cyber-core-container {
        position: relative;
        width: 190px;
        height: 190px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        border-radius: 50%;
    }

    .logo-spheric-backdrop {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: radial-gradient(circle at 50% 50%, rgba(9, 15, 32, 0.85) 0%, rgba(3, 7, 18, 0.98) 80%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        box-shadow:
            0 0 35px rgba(6, 182, 212, 0.15),
            inset 0 0 20px rgba(138, 43, 226, 0.2),
            inset 0 0 4px rgba(255, 255, 255, 0.05);
        z-index: 11;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        animation: techPulse 4s ease-in-out infinite alternate;
    }

    @keyframes techPulse {
        0% {
            border-color: rgba(6, 182, 212, 0.25);
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.1), inset 0 0 20px rgba(138, 43, 226, 0.15);
        }
        100% {
            border-color: rgba(138, 43, 226, 0.4);
            box-shadow: 0 0 40px rgba(138, 43, 226, 0.2), inset 0 0 30px rgba(6, 182, 212, 0.25);
        }
    }

    .tech-logo-center img {
        width: 70%;
        height: 70%;
        object-fit: contain;
        border-radius: 50%;
        mix-blend-mode: lighten;
        filter: drop-shadow(0 0 15px rgba(6, 182, 212, 0.5));
        z-index: 12;
        transition: all 0.5s ease;
        animation: coreFloat 4s ease-in-out infinite alternate;
    }

    @keyframes coreFloat {
        0% { transform: translateY(-3px) scale(1); }
        100% { transform: translateY(3px) scale(1.03); }
    }

    .logo-animation-stage:hover .tech-logo-center {
        transform: scale(1.06);
        cursor: pointer;
    }

    .logo-animation-stage:hover .logo-spheric-backdrop {
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow:
            0 0 50px rgba(6, 182, 212, 0.35),
            inset 0 0 35px rgba(138, 43, 226, 0.4);
    }

    .logo-animation-stage:hover .tech-logo-center img {
        filter: drop-shadow(0 0 25px rgba(6, 182, 212, 0.8)) brightness(1.1);
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

    .ring-1 {
        width: 260px;
        height: 260px;
        border: 1px dashed rgba(6, 182, 212, 0.2);
        animation: rotateClockwise 25s linear infinite;
    }

    .ring-2 {
        width: 360px;
        height: 360px;
        border: 1px dotted rgba(138, 43, 226, 0.25);
        animation: rotateCounterClockwise 35s linear infinite;
    }

    .ring-3 {
        width: 460px;
        height: 460px;
        border: 1px solid rgba(255, 255, 255, 0.03);
        animation: rotateClockwise 50s linear infinite;
    }

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
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        animation: luxPulse 2s infinite ease-in-out;
    }

    @keyframes luxPulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }

    .node-cyan {
        background: #06b6d4;
        box-shadow: 0 0 12px #06b6d4, 0 0 25px rgba(6, 182, 212, 0.6);
    }

    .node-purple {
        background: #8a2be2;
        box-shadow: 0 0 12px #8a2be2, 0 0 25px rgba(138, 43, 226, 0.6);
    }

    .node-blue {
        background: #007bff;
        box-shadow: 0 0 12px #007bff, 0 0 25px rgba(0, 123, 255, 0.6);
    }

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

    @keyframes rotateClockwise {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes rotateCounterClockwise {
        0% { transform: rotate(360deg); }
        100% { transform: rotate(0deg); }
    }

    @keyframes beamPulse {
        0% { transform: scale(0.85); opacity: 0.3; }
        100% { transform: scale(1.1); opacity: 0.8; }
    }

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

    .quantum-node:hover {
        transform: scale(1.4);
    }

    .quantum-node:hover .lux-floating-label {
        opacity: 1;
        transform: translateY(-35px);
    }

    .section-wrapper {
        padding: 100px 0;
        position: relative;
        z-index: 3;
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
        box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
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
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0; left: 0; z-index: 1;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .subl-banner-card .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.15) grayscale(0.2);
        transition: all 0.5s ease;
    }

    .subl-banner-card:hover {
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 40px rgba(6, 182, 212, 0.05);
        transform: translateY(-5px);
    }

    .subl-banner-card:hover .img-container {
        transform: scale(1.04);
    }

    .subl-banner-card:hover .img-container img {
        filter: brightness(0.25) saturate(120%);
    }

    .card-overlay-details {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        padding: 40px 35px;
        z-index: 3;
        background: linear-gradient(to top, rgba(3, 7, 18, 0.95) 45%, transparent 100%);
    }

    .card-label-mono {
        font-family: monospace;
        font-size: 0.75rem;
        color: #06b6d4;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .card-headline-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }

    .card-paragraph-desc {
        color: #9ca3af !important;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .metrics-row {
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        padding: 55px 0;
        margin: 20px 0 60px 0;
        background: rgba(255, 255, 255, 0.01);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .metric-item h4 {
        font-size: 2.6rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 6px;
        letter-spacing: -1.5px;
        background: linear-gradient(to bottom, #ffffff, #9ca3af);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .metric-item p {
        font-family: monospace;
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0;
        letter-spacing: 2px;
    }

    .pipeline-container {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important;
        border-radius: 32px;
        padding: 60px;
        position: relative;
    }

    .pipeline-header {
        margin-bottom: 45px;
    }

    .pipeline-title {
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        font-weight: 800;
        letter-spacing: -1.5px;
        line-height: 1.1;
    }

    .pipeline-progress-bar {
        height: 2px;
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        position: relative;
        margin: 40px 0;
        border-radius: 2px;
        overflow: hidden;
    }

    .pipeline-progress-fill {
        height: 100%;
        width: 75%;
        background: linear-gradient(90deg, #007bff, #06b6d4, #8a2be2);
        animation: activeCore 3s ease-in-out infinite alternate;
    }

    @keyframes activeCore {
        0% { opacity: 0.6; width: 45%; }
        100% { opacity: 1; width: 80%; }
    }

    .pipeline-step-box {
        padding: 25px;
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .pipeline-step-box:hover {
        border-color: rgba(6, 182, 212, 0.2);
        background: rgba(255, 255, 255, 0.03);
    }

    .pipeline-step-box h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pipeline-step-box h4 span {
        font-family: monospace;
        font-size: 0.8rem;
        color: #06b6d4;
        background: rgba(6, 182, 212, 0.06);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .pipeline-step-box p {
        font-size: 0.88rem;
        color: #9ca3af;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .tech-showcase-section {
        padding: 100px 0 40px 0;
    }

    .showcase-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .showcase-card {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .showcase-card:hover {
        transform: translateY(-6px);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 50px rgba(138, 43, 226, 0.05);
    }

    .showcase-img-wrap {
        height: 240px;
        width: 100%;
        overflow: hidden;
        position: relative;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        background: #030712;
    }

    .showcase-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.65);
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .showcase-card:hover .showcase-img-wrap img {
        transform: scale(1.04);
        filter: brightness(0.8);
    }

    .showcase-body {
        padding: 35px;
    }

    .showcase-tag {
        font-family: monospace;
        font-size: 0.75rem;
        color: #06b6d4;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .showcase-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 14px;
    }

    .showcase-desc {
        font-size: 0.9rem;
        color: #9ca3af;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .interactive-grid-infra {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-top: 50px;
    }

    .infra-interactive-card {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4) !important;
        padding: 45px 35px;
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .infra-interactive-card::after {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.03) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }

    .infra-interactive-card:hover {
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .infra-interactive-card:hover::after {
        opacity: 1;
    }

    .infra-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .infra-card-title i {
        font-size: 0.95rem;
        color: #007bff;
        transition: all 0.3s ease;
    }

    .infra-interactive-card:hover .infra-card-title i {
        color: #06b6d4;
        transform: scale(1.1);
    }

    .infra-card-desc {
        color: #9ca3af;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .main-footer {
        background: #030712 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.04) !important;
        padding: 20px !important;
        font-size: 0.85rem !important;
        color: #6b7280 !important;
        text-align: center !important;
    }

    .main-footer strong, .main-footer a {
        color: #9ca3af !important;
        text-decoration: none !important;
        letter-spacing: 0.5px !important;
    }

    .main-footer a:hover {
        color: #06b6d4 !important;
    }
</style>

<div class="main-terminal">

    <section class="tech-hero-section container">
        <div class="row align-items-center w-100 g-5">
            <div class="col-12 col-lg-6">
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
                    <a href="https://wa.me/tu_numero" target="_blank" class="btn-ohio-text">
                        <span>Iniciar Consultoría Técnica</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6 d-none d-lg-block">
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

                    <div class="cyber-core-container">
                        <div class="cyber-mainframe"></div>
                        <div class="cyber-mainframe-inner"></div>
                        <div class="cyber-core-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
    <div class="container my-4 reveal">
        <div class="auth-banner-panel d-flex align-items-center justify-content-between" style="background: #090d16 !important; border: 1px solid rgba(138, 43, 226, 0.4) !important; border-left: 5px solid #8a2be2 !important; padding: 25px 35px !important; border-radius: 16px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.6) !important;">
            <div>
                <span style="font-family: monospace !important; font-size: 0.95rem !important; color: #a78bfa !important; font-weight: 700 !important; letter-spacing: 1px !important; display: block !important; margin-bottom: 6px !important;">NÚCLEO INICIADO // {{ Auth::user()->name }}</span>
                <p style="color: #ffffff !important; font-size: 0.9rem !important; margin-bottom: 0 !important; font-weight: 400 !important; opacity: 1 !important; text-shadow: 0 1px 2px rgba(0,0,0,0.8) !important;">Portal administrativo activo. Los módulos internos de control y gestión se encuentran enlazados de forma transparente.</p>
            </div>
            <div style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 12px #06b6d4;"></div>
        </div>
    </div>
    @endauth

    <section class="section-wrapper container reveal">
        <span class="ohio-headline-label">Capacidades</span>
        <h2 class="text-white fw-800 mb-5" style="font-size: 2.2rem; letter-spacing: -1px;">Soluciones de software corporativo.</h2>

        <div class="row g-4">
            @php
            $servicios = [
                ['label' => 'SISTEMAS SAAS', 'title' => 'Plataformas Web', 'desc' => 'Desarrollo de aplicaciones modulares completas full-stack estructuradas con la robustez lógica del framework Laravel.', 'img' => 'photo-1451187580459-43490279c0fa'],
                ['label' => 'EXPERTISE MÓVIL', 'title' => 'Aplicaciones Nativas', 'desc' => 'Ecosistemas móviles fluidos con persistencia segura de información y alto rendimiento de consumo cloud.', 'img' => 'photo-1550751827-4bd374c3f58b'],
                ['label' => 'CIBERSEGURIDAD', 'title' => 'Auditorías SAST', 'desc' => 'Inspección y análisis estático automatizado de código para neutralizar vulnerabilidades críticas antes del despliegue real.', 'img' => 'photo-1518770660439-4636190af475'],
                ['label' => 'AUTOMATIZACIÓN', 'title' => 'Sistemas Inteligentes', 'desc' => 'Modelos analíticos de Inteligencia Artificial para el procesamiento lógico y optimización de flujos de datos.', 'img' => 'photo-1555066931-4365d14bab8c']
            ];
            @endphp

            @foreach($servicios as $s)
            <div class="col-12 col-md-6 col-lg-3">
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

    <div class="main-terminal">
        <div class="row metrics-row text-center g-4 mx-0">
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
    </div>

    <section class="container reveal" style="padding: 40px 0;">
        <div class="pipeline-container">
            <div class="pipeline-header">
                <span class="ohio-headline-label">Ecosistema DevOps</span>
                <h3 class="pipeline-title text-white">Ciclo de integración continuo.</h3>
            </div>

            <div class="pipeline-progress-bar">
                <div class="pipeline-progress-fill"></div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-md-4">
                    <div class="pipeline-step-box">
                        <h4><span>01</span> Desarrollo Local</h4>
                        <p>Ambientes aislados e independientes para la programación y estructuración de la arquitectura base sin alterar sistemas activos.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="pipeline-step-box">
                        <h4><span>02</span> Entorno de Pruebas</h4>
                        <p>Fase intermedia automatizada (Staging) dedicada al análisis SAST exhaustivo y validación de flujos lógicos con datos simulados.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="pipeline-step-box">
                        <h4><span>03</span> Despliegue Productivo</h4>
                        <p>Lanzamiento seguro y optimizado a servidores reales de producción, garantizando cero caídas de servicio de cara al cliente.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tech-showcase-section container reveal">
        <span class="ohio-headline-label">Showcase</span>
        <h2 class="text-white fw-800" style="font-size: 2.2rem; letter-spacing: -1px;">Módulos e Infraestructura Desarrollada.</h2>

        <div class="showcase-grid">
            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Oraculo_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech IA Oráculo">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Software Tech Oráculo</span>
                    <h3 class="showcase-title">Oraculo Chronos Engine</h3>
                    <p class="showcase-desc">Agente cognitivo avanzado que automatiza flujos de depuración y optimización sintáctica. Analiza de manera predictiva el código fuente para erradicar errores de compilación y fallas de seguridad lógicas antes del despliegue productivo.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Guardian_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech Guardián">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Software Tech Guardián</span>
                    <h3 class="showcase-title">Guardian Code-Shield</h3>
                    <p class="showcase-desc">Núcleo perimetral dedicado al blindaje criptográfico de metadatos y bases de datos corporativas. Neutraliza virus, malware y accesos no autorizados, garantizando la máxima integridad y persistencia segura de la información de los clientes.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Nexus_Logotipo_ConFondo_SoftwareTechnologies.png') }}" alt="Software Tech Nexus">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Software Tech Nexus</span>
                    <h3 class="showcase-title">Nexus Blueprint Studio</h3>
                    <p class="showcase-desc">Motor automatizado de arquitectura que compila plataformas web, aplicaciones y APIs en minutos mediante lenguaje natural. Elimina la barrera técnica para creadores digitales con despliegues instantáneos y acelera la producción en entornos corporativos.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container section-wrapper reveal" style="padding-bottom: 120px;">
        <span class="ohio-headline-label">Infraestructura</span>
        <h2 class="text-white fw-800" style="font-size: 2.2rem; letter-spacing: -1px;">Estándar operativo.</h2>

        <div class="interactive-grid-infra">
            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-rocket"></i> Misión de Estabilidad
                </h3>
                <p class="infra-card-desc">Acelerar la transformación digital mediante el despliegles de lógicas web limpias, modulares e inmunes al fallo de concurrencia.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-eye"></i> Visión Tecnológica
                </h3>
                <p class="infra-card-desc">Establecer a Software Tech como el sello de confianza definitivo en ingeniería de sistemas avanzados y ciberseguridad corporativa.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-lock"></i> Integridad Criptográfica
                </h3>
                <p class="infra-card-desc">Garantizar una transparencia absoluta en la ingeniería de bases de datos, resguardando los activos lógicos corporativos bajo blindaje preventivo.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-bolt"></i> Agilidad Operativa
                </h3>
                <p class="infra-card-desc">Ejecución fluida mediante pipelines de integración continua, reduciendo drásticamente los tiempos muertos en entornos productivos.</p>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} <a href="/home">SOFTWARE TECH</a> | INNOVATION LAB | V4.5</strong>
    </footer>
</div>

<script>
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

    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 80;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }
    window.addEventListener("scroll", reveal);
    window.addEventListener("load", reveal);
</script>
@endsection
