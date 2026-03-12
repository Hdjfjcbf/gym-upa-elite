<?php 
$status = $_GET['status'] ?? null; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GYM UPA | Recuperar Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-green: #00ff88; 
            --accent-chrome: #e0e0e0;
            --dark-glass: rgba(0, 20, 10, 0.4);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #000; height: 100vh; display: flex;
            justify-content: center; align-items: center;
            overflow: hidden; font-family: 'Inter', sans-serif;
        }
        canvas { position: fixed; top: 0; left: 0; z-index: -1; }

        /* Alerta Elite Verde */
        .alert-badge {
            position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
            background: rgba(0, 255, 136, 0.1); backdrop-filter: blur(10px);
            border: 1px solid var(--accent-green); padding: 12px 25px;
            border-radius: 15px; color: #fff; font-weight: 800; font-size: 0.8rem;
            animation: slideDown 0.5s ease forwards; z-index: 10;
        }
        @keyframes slideDown { from { top: -60px; } to { top: 20px; } }

        .glass-card {
            position: relative; background: var(--dark-glass);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border-radius: 50px; width: 95%; max-width: 480px;
            padding: 4rem; text-align: center;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(0, 255, 136, 0.2);
        }
        .brand h1 {
            font-family: 'Montserrat', sans-serif; font-size: 3rem; 
            color: #fff; font-weight: 900; letter-spacing: -2px;
            line-height: 0.85; margin-bottom: 10px;
        }
        .brand h1 span { color: var(--accent-green); text-shadow: 0 0 20px rgba(0, 255, 136, 0.6); }
        .brand p {
            color: var(--accent-chrome); font-size: 0.9rem; letter-spacing: 4px;
            margin-bottom: 3.5rem; text-transform: uppercase; font-weight: 800;
        }
        .form-group { margin-bottom: 2rem; text-align: left; }
        .form-group label {
            display: block; font-size: 0.9rem; color: var(--accent-green);
            margin-bottom: 12px; font-weight: 800;
        }
        .form-group input {
            width: 100%; background: rgba(0, 10, 5, 0.7);
            border: 1px solid var(--accent-green); padding: 20px;
            border-radius: 20px; color: #fff; font-size: 1.1rem;
            outline: none; transition: 0.3s;
        }
        .btn-elite {
            width: 100%; background: var(--accent-green); color: #000; 
            padding: 24px; border-radius: 20px; font-weight: 900;
            font-size: 1.1rem; border: none; cursor: pointer;
            text-transform: uppercase; box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
            transition: 0.3s;
        }
        .btn-elite:hover { background: #00cc6d; transform: scale(1.02); }
        .back-link { margin-top: 2rem; }
        .back-link a { color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.8rem; font-weight: 600; }
        .back-link a:hover { color: var(--accent-green); }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>

    <?php if($status): ?>
    <div class="alert-badge">
        <?php 
            if($status == 'sent') echo "✅ CÓDIGO ENVIADO A TU CORREO";
            else if($status == 'notfound') echo "❌ EL CORREO NO ESTÁ REGISTRADO";
            else echo "⚠️ HUBO UN ERROR, INTENTA DE NUEVO";
        ?>
    </div>
    <?php endif; ?>

    <div class="glass-card">
        <div class="brand">
            <h1>RESETEO <span>ELITE</span></h1>
            <p>Recuperar Contraseña</p>
        </div>
        <form action="../../api/recuperar_proceso.php" method="POST">
            <div class="form-group">
                <label>INGRESA TU CORREO</label>
                <input type="email" name="correo" 
                       placeholder="usuario@upatlacomulco.edu.mx" 
                       pattern=".+@upatlacomulco\.edu\.mx"
                       title="Usa tu correo institucional" required>
            </div>
            <button type="submit" class="btn-elite">ENVIAR CÓDIGO</button>
        </form>
        <div class="back-link">
            <a href="login.php">← VOLVER AL INICIO DE SESIÓN</a>
        </div>
    </div>

    <script type="module">
        import { Renderer, Program, Mesh, Triangle } from 'https://cdn.skypack.dev/ogl';
        const vertex = `attribute vec2 position; void main() { gl_Position = vec4(position, 0, 1); }`;
        const fragment = `
            precision highp float;
            uniform float uTime;
            uniform vec2 uResolution;
            vec2 hash(vec2 p) {
                p = vec2(dot(p, vec2(127.1, 311.7)), dot(p, vec2(269.5, 183.3)));
                return fract(sin(p) * 43758.5453) * 2.0 - 1.0;
            }
            float noise(vec2 p) {
                vec2 i = floor(p); vec2 f = fract(p);
                vec2 u = f*f*(3.0-2.0*f);
                return mix(mix(dot(hash(i + vec2(0,0)), f - vec2(0,0)),
                           dot(hash(i + vec2(1,0)), f - vec2(1,0)), u.x),
                           mix(dot(hash(i + vec2(0,1)), f - vec2(0,1)),
                           dot(hash(i + vec2(1,1)), f - vec2(1,1)), u.x), u.y);
            }
            float fbm(vec2 p) {
                float v = 0.0; float a = 0.5;
                for (int i = 0; i < 5; i++) {
                    v += a * noise(p); p *= 2.0; a *= 0.5;
                }
                return v;
            }
            void main() {
                vec2 uv = gl_FragCoord.xy / uResolution.xy;
                vec2 p = (uv * 2.0 - 1.0) * 1.5;
                p.x *= uResolution.x / uResolution.y;
                float t = uTime * 0.1; 
                vec2 q = vec2(fbm(p + t * 0.1), fbm(p + vec2(5.2, 1.3)));
                vec2 r = vec2(fbm(p + 4.0 * q + vec2(1.7, 9.2) + t), fbm(p + 4.0 * q + vec2(8.3, 2.8) + t));
                float f = fbm(p + 4.0 * r);
                vec3 col = mix(vec3(0.0), vec3(0.0, 0.3, 0.15), clamp((f*f)*4.0, 0.0, 1.0));
                col += 0.4 * pow(f, 3.0) * vec3(0.6, 1.0, 0.8);
                col *= 1.3;
                gl_FragColor = vec4(col, 1.0);
            }
        `;
        const renderer = new Renderer({ canvas: document.getElementById('canvas'), dpr: 1 });
        const gl = renderer.gl;
        const program = new Program(gl, {
            vertex, fragment,
            uniforms: { uTime: { value: 0 }, uResolution: { value: [0, 0] } }
        });
        const mesh = new Mesh(gl, { geometry: new Triangle(gl), program });
        function resize() {
            renderer.setSize(window.innerWidth, window.innerHeight);
            program.uniforms.uResolution.value = [window.innerWidth, window.innerHeight];
        }
        window.addEventListener('resize', resize);
        resize();
        function update(t) {
            program.uniforms.uTime.value = t * 0.001;
            renderer.render({ scene: mesh });
            requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    </script>
</body>
</html>