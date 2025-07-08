// Three.js Animation
let scene, camera, renderer, pot, plant;

function init() {
  // Create scene
  scene = new THREE.Scene();

  // Create camera
  camera = new THREE.PerspectiveCamera(
    75,
    window.innerWidth / window.innerHeight,
    0.1,
    1000
  );
  camera.position.z = 5;

  // Create renderer
  renderer = new THREE.WebGLRenderer({
    canvas: document.getElementById("three-canvas"),
    alpha: true,
  });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(window.devicePixelRatio);

  // Add lights
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
  scene.add(ambientLight);

  const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
  directionalLight.position.set(0, 1, 1);
  scene.add(directionalLight);

  // Create pot
  const potGeometry = new THREE.CylinderGeometry(1, 0.8, 1.5, 32);
  const potMaterial = new THREE.MeshPhongMaterial({
    color: 0x8b4513,
    shininess: 30,
  });
  pot = new THREE.Mesh(potGeometry, potMaterial);
  scene.add(pot);

  // Create plant
  const plantGeometry = new THREE.ConeGeometry(0.5, 2, 32);
  const plantMaterial = new THREE.MeshPhongMaterial({
    color: 0x228b22,
    shininess: 30,
  });
  plant = new THREE.Mesh(plantGeometry, plantMaterial);
  plant.position.y = 1.5;
  scene.add(plant);

  // Add leaves
  for (let i = 0; i < 8; i++) {
    const leafGeometry = new THREE.SphereGeometry(0.3, 32, 32);
    const leafMaterial = new THREE.MeshPhongMaterial({
      color: 0x228b22,
      shininess: 30,
    });
    const leaf = new THREE.Mesh(leafGeometry, leafMaterial);

    const angle = (i / 8) * Math.PI * 2;
    const radius = 0.8;
    leaf.position.x = Math.cos(angle) * radius;
    leaf.position.z = Math.sin(angle) * radius;
    leaf.position.y = 1.5;

    scene.add(leaf);
  }

  // Handle window resize
  window.addEventListener("resize", onWindowResize, false);
}

function onWindowResize() {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
}

function animate() {
  requestAnimationFrame(animate);

  // Rotate the pot and plant
  pot.rotation.y += 0.005;
  plant.rotation.y += 0.005;

  // Gentle swaying motion for the plant
  plant.position.y = 1.5 + Math.sin(Date.now() * 0.001) * 0.1;

  renderer.render(scene, camera);
}

// Initialize and start animation
init();
animate();
