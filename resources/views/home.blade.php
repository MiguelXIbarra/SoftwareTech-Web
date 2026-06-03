@extends('layouts.app')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700;800&display=swap');

    .main-terminal {
        background: #020408 !important;
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
        background: radial-gradient(circle at 85% 15%, rgba(138, 43, 226, 0.05) 0%, transparent 45%),
            radial-gradient(circle at 15% 55%, rgba(0, 123, 255, 0.03) 0%, transparent 40%),
            radial-gradient(circle at 50% 85%, rgba(138, 43, 226, 0.03) 0%, transparent 45%);
        z-index: 1;
        pointer-events: none;
    }

    .navbar {
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background: rgba(2, 4, 8, 0.6) !important;
        backdrop-filter: blur(25px) !important;
        -webkit-backdrop-filter: blur(25px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
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
        min-height: 180px;
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
        margin-bottom: 8px;
    }

    .hero-headline .dna-gradient-text {
        background: linear-gradient(135deg, #007bff, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: block;
    }

    .typed-cursor {
        color: #8a2be2;
        font-weight: 300;
        animation: blink 0.7s infinite;
        margin-left: 4px;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .hero-subtext {
        font-size: 1.2rem;
        color: #8e9aa8;
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
        background: #060913;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 16px 32px;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .btn-ohio-text:hover {
        border-color: rgba(138, 43, 226, 0.4);
        box-shadow: 0 0 30px rgba(138, 43, 226, 0.15);
        transform: translateY(-2px);
    }

    .btn-ohio-text i {
        color: #007bff;
        transition: transform 0.3s ease;
    }

    .btn-ohio-text:hover i {
        transform: translateX(6px);
        color: #8a2be2;
    }

    .lux-cluster-stage {
        position: relative;
        width: 100%;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        perspective: 1200px;
    }

    .lux-grid-floor {
        position: absolute;
        width: 85%;
        height: 85%;
        background-image:
            linear-gradient(rgba(0, 123, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(138, 43, 226, 0.03) 1px, transparent 1px);
        background-size: 30px 30px;
        transform: rotateX(55deg) rotateZ(-45deg) translateZ(-40px);
        border: 1px solid rgba(255, 255, 255, 0.01);
        border-radius: 24px;
        opacity: 0.8;
    }

    .lux-glass-plate {
        position: absolute;
        width: 75%;
        height: 65%;
        background: rgba(6, 9, 19, 0.6);
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        border-left: 1px solid rgba(255, 255, 255, 0.02);
        border-radius: 32px;
        transform: rotateX(55deg) rotateZ(-45deg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: -20px 30px 60px rgba(0, 0, 0, 0.7);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .lux-cluster-stage:hover .lux-glass-plate {
        transform: rotateX(50deg) rotateZ(-40deg) translateZ(15px);
        border-top-color: rgba(138, 43, 226, 0.3);
        box-shadow: -30px 45px 80px rgba(0, 0, 0, 0.8);
    }

    .lux-node-system {
        position: absolute;
        width: 100%;
        height: 100%;
        transform: rotateX(55deg) rotateZ(-45deg);
        transform-style: preserve-3d;
        z-index: 5;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .lux-cluster-stage:hover .lux-node-system {
        transform: rotateX(50deg) rotateZ(-40deg) translateZ(25px);
    }

    .lux-core-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px dashed rgba(0, 123, 255, 0.15);
        animation: spinRing 20s linear infinite;
    }

    @keyframes spinRing {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .lux-dot-anchor {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        transition: all 0.4s ease;
    }

    .lux-dot-anchor::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        animation: luxPulse 2s infinite ease-in-out;
    }

    @keyframes luxPulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(4);
            opacity: 0;
        }
    }

    .anchor-web {
        top: 20%;
        left: 25%;
        background: #007bff;
        box-shadow: 0 0 20px #007bff;
    }

    .anchor-sast {
        top: 30%;
        left: 75%;
        background: #8a2be2;
        box-shadow: 0 0 20px #8a2be2;
    }

    .anchor-cloud {
        top: 75%;
        left: 35%;
        background: #ffffff;
        box-shadow: 0 0 20px #ffffff;
    }

    .anchor-data {
        top: 70%;
        left: 70%;
        background: #007bff;
        box-shadow: 0 0 20px #007bff;
    }

    .lux-floating-label {
        position: absolute;
        background: #060913;
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 6px 12px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.7rem;
        color: #8e9aa8;
        opacity: 0;
        transform: translateZ(20px) translateY(10px);
        transition: all 0.3s ease;
        pointer-events: none;
        white-space: nowrap;
    }

    .lux-dot-anchor:hover {
        transform: scale(1.4);
    }

    .lux-dot-anchor:hover .lux-floating-label {
        opacity: 1;
        transform: translateZ(30px) translateY(-30px);
    }

    .lux-laser-line {
        position: absolute;
        background: linear-gradient(90deg, transparent, rgba(138, 43, 226, 0.2), transparent);
        height: 1px;
        width: 140px;
        top: 45%;
        left: 25%;
        transform: rotate(35deg);
    }

    .section-wrapper {
        padding: 120px 0;
        position: relative;
        z-index: 3;
    }

    .ohio-headline-label {
        font-family: monospace;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: #8a2be2;
        margin-bottom: 20px;
        display: block;
        font-weight: 700;
    }

    .subl-banner-card {
        position: relative;
        border-radius: 28px;
        overflow: hidden;
        height: 430px;
        background: #05070c;
        border: 1px solid rgba(255, 255, 255, 0.03);
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .subl-banner-card .img-container {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .subl-banner-card .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.12) grayscale(0.2);
        transition: all 0.5s ease;
    }

    .subl-banner-card:hover {
        border-color: rgba(0, 123, 255, 0.2);
        background: #070b14;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8);
        transform: translateY(-5px);
    }

    .subl-banner-card:hover .img-container {
        transform: scale(1.04);
    }

    .subl-banner-card:hover .img-container img {
        filter: brightness(0.25);
    }

    .card-overlay-details {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 40px 35px;
        z-index: 3;
        background: linear-gradient(to top, #05070c 40%, transparent 100%);
    }

    .card-headline-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }

    .card-paragraph-desc {
        color: #8e9aa8 !important;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .metrics-row {
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        padding: 55px 0;
        margin: 40px 0 80px 0;
        background: #03050a;
    }

    .metric-item h4 {
        font-size: 2.6rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 6px;
        letter-spacing: -1.5px;
        background: linear-gradient(to bottom, #ffffff, #8e9aa8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .metric-item p {
        font-family: monospace;
        font-size: 0.75rem;
        color: #4b5563;
        text-transform: uppercase;
        margin-bottom: 0;
        letter-spacing: 2px;
    }

    .pipeline-container {
        background: #04060b;
        border: 1px solid rgba(255, 255, 255, 0.02);
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
        background: rgba(255, 255, 255, 0.03);
        position: relative;
        margin: 40px 0;
        border-radius: 2px;
        overflow: hidden;
    }

    .pipeline-progress-fill {
        height: 100%;
        width: 75%;
        background: linear-gradient(90deg, #007bff, #8a2be2);
        animation: activeCore 3s ease-in-out infinite alternate;
    }

    @keyframes activeCore {
        0% {
            opacity: 0.6;
            width: 45%;
        }

        100% {
            opacity: 1;
            width: 80%;
        }
    }

    .pipeline-step-box {
        padding: 20px;
        background: #060911;
        border: 1px solid rgba(255, 255, 255, 0.01);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .pipeline-step-box:hover {
        border-color: rgba(0, 123, 255, 0.15);
        background: #080d1a;
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
        color: #007bff;
        background: rgba(0, 123, 255, 0.06);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .pipeline-step-box p {
        font-size: 0.88rem;
        color: #8e9aa8;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .tech-showcase-section {
        padding: 120px 0 60px 0;
    }

    .showcase-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .showcase-card {
        background: #04060b;
        border: 1px solid rgba(255, 255, 255, 0.02);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .showcase-card:hover {
        transform: translateY(-6px);
        border-color: rgba(138, 43, 226, 0.2);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
    }

    .showcase-img-wrap {
        height: 220px;
        width: 100%;
        overflow: hidden;
        position: relative;
        border-bottom: 1px solid rgba(255, 255, 255, 0.01);
    }

    .showcase-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.6);
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .showcase-card:hover .showcase-img-wrap img {
        transform: scale(1.05);
        filter: brightness(0.8);
    }

    .showcase-body {
        padding: 30px;
    }

    .showcase-tag {
        font-family: monospace;
        font-size: 0.75rem;
        color: #8a2be2;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 10px;
    }

    .showcase-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 12px;
    }

    .showcase-desc {
        font-size: 0.88rem;
        color: #8e9aa8;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .interactive-grid-infra {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-top: 50px;
    }

    .infra-interactive-card {
        background: #04060b;
        border: 1px solid rgba(255, 255, 255, 0.02);
        padding: 45px 35px;
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .infra-interactive-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(138, 43, 226, 0.02) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }

    .infra-interactive-card:hover {
        border-color: rgba(138, 43, 226, 0.2);
        background: #060911;
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
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
        color: #8a2be2;
        transform: scale(1.1);
    }

    .infra-card-desc {
        color: #8e9aa8;
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

    .site-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        padding: 60px 0 30px 0;
        color: #4b5563;
        position: relative;
        z-index: 3;
        background: #020408;
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
                            <span class="dna-gradient-text" id="typed-target">arquitecturas web.</span><span
                                class="typed-cursor">|</span>
                        </h1>
                    </div>
                    <p class="hero-subtext">
                        Diseñamos sistemas modulares de alto rendimiento e infraestructura blindada a la medida para la
                        automatización operativa de tu empresa.
                    </p>
                    <a href="https://wa.me/tu_numero" target="_blank" class="btn-ohio-text">
                        <span>Iniciar Consultoría Técnica</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6 d-none d-lg-block">
                <div class="lux-cluster-stage">
                    <div class="lux-grid-floor"></div>
                    <div class="lux-glass-plate"></div>
                    <div class="lux-node-system">
                        <div class="lux-core-ring" style="width: 180px; height: 180px; top: 25%; left: 25%;"></div>
                        <div class="lux-laser-line"></div>
                        <div class="lux-dot-anchor anchor-web">
                            <div class="lux-floating-label">Laravel Full-Stack Engine</div>
                        </div>
                        <div class="lux-dot-anchor anchor-sast">
                            <div class="lux-floating-label">SAST Shield Active</div>
                        </div>
                        <div class="lux-dot-anchor anchor-cloud">
                            <div class="lux-floating-label">Cloud Cluster Node</div>
                        </div>
                        <div class="lux-dot-anchor anchor-data">
                            <div class="lux-floating-label">Data Pipeline Core</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
    <div class="container my-4 reveal">
        <div class="auth-banner-panel d-flex align-items-center justify-content-between" style="background: #090d16; border: 1px solid rgba(138, 43, 226, 0.3); border-left: 5px solid #8a2be2; padding: 25px 35px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.6);">
            <div>
                <span style="font-family: monospace; font-size: 0.9rem; color: #a78bfa; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 6px;">NÚCLEO INICIADO // {{ Auth::user()->name }}</span>
                <p style="color: #e2e8f0 !important; font-size: 0.9rem; margin-bottom: 0; font-weight: 400; opacity: 1 !important;">Portal administrativo activo. Los módulos internos de control y gestión se encuentran enlazados de forma transparente.</p>
            </div>
            <div style="width: 8px; height: 8px; background: #007bff; border-radius: 50%; box-shadow: 0 0 12px #007bff;"></div>
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
                        <p>Ambientes aislados e independientes para la programación y estructuración de la arquitectura
                            base sin alterar sistemas activos.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="pipeline-step-box">
                        <h4><span>02</span> Entorno de Pruebas</h4>
                        <p>Fase intermedia automatizada (Staging) dedicada al análisis SAST exhaustivo y validación de
                            flujos lógicos con datos simulados.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="pipeline-step-box">
                        <h4><span>03</span> Despliegue Productivo</h4>
                        <p>Lanzamiento seguro y optimizado a servidores reales de producción, garantizando cero caídas
                            de servicio de cara al cliente.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tech-showcase-section container reveal">
        <span class="ohio-headline-label">Showcase</span>
        <h2 class="text-white fw-800" style="font-size: 2.2rem; letter-spacing: -1px;">Módulos e Infraestructura
            Desarrollada.</h2>

        <div class="showcase-grid">
            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Oraculo_Logotipo_ConFondo_SoftwareTechnologies.png') }}"
                        alt="Software Tech IA Oráculo">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Oráculo</span>
                    <h3 class="showcase-title">Inteligencia Artificial</h3>
                    <p class="showcase-desc">Agente cognitivo especializado en auditoría estática avanzada y
                        optimización
                        sintáctica. Ejecuta flujos automatizados de depuración y análisis predictivo de código fuente
                        para
                        garantizar despliegues productivos con tolerancia cero a fallos de compilación y
                        vulnerabilidades
                        lógicas estructurales.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Guardian_Logotipo_ConFondo_SoftwareTechnologies.png') }}"
                        alt="Software Tech Guardián">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Guardián</span>
                    <h3 class="showcase-title">Core de Seguridad</h3>
                    <p class="showcase-desc">Núcleo perimetral dedicado al blindaje criptográfico de metadatos y activos
                        digitales corporativos. Mitiga proactivamente vectores de ataque, inyecciones maliciosas y
                        filtraciones, estableciendo un estándar hermético para la persistencia e integridad de datos
                        sensibles de tus clientes.</p>
                </div>
            </div>

            <div class="showcase-card">
                <div class="showcase-img-wrap">
                    <img src="{{ asset('images/Nexus_Logotipo_ConFondo_SoftwareTechnologies.png') }}"
                        alt="Software Tech Nexus">
                </div>
                <div class="showcase-body">
                    <span class="showcase-tag">Nexus</span>
                    <h3 class="showcase-title">Predicción de Datos</h3>
                    <p class="showcase-desc">Motor inteligente de automatización de flujos y control de tiempos
                        operativos.
                        Optimiza de manera exponencial la gestión de inventarios y control de stock en almacenes
                        complejos,
                        transformando procesos manuales críticos de horas en ciclos de ejecución eficientes de minutos.
                    </p>
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
                <p class="infra-card-desc">Acelerar la transformación digital mediante el despliegue de lógicas web
                    limpias, modulares e inmunes al fallo de concurrencia.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-eye"></i> Visión Tecnológica
                </h3>
                <p class="infra-card-desc">Establecer a Software Tech como el sello de confianza definitivo en
                    ingeniería de sistemas avanzados y ciberseguridad corporativa.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-lock"></i> Integridad Criptográfica
                </h3>
                <p class="infra-card-desc">Garantizar una transparencia absoluta en la ingeniería de bases de datos,
                    resguardando los activos lógicos corporativos bajo blindaje preventivo.</p>
            </div>

            <div class="infra-interactive-card">
                <h3 class="infra-card-title">
                    <i class="fas fa-bolt"></i> Agilidad Operativa
                </h3>
                <p class="infra-card-desc">Ejecución fluida mediante pipelines de integración continua, reduciendo
                    drásticamente los tiempos muertos en entornos productivos.</p>
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
