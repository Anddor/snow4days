window.onload = function () {
    "use strict";
    
    var particles = [];
    var canvas = document.getElementById("particle-canvas");
    var navbar = document.getElementById("navbar");
    canvas.width = window.innerWidth;
    canvas.height = 100;
    
    var context = canvas.getContext("2d");
    var maxParticles = 50;
    
    function createParticle() {
    
        // Each "particle" is created as an object containing x and y pos, a radius, and a horisontal speed.
        var x, y, radius, speed, particle;
        x = 0;
        y = (Math.random()) * canvas.height;
        radius = Math.random() * 5;
        speed = Math.random() * 2 + 5;

        particle = {radius: radius, speed: speed};
        Object.defineProperties(particle, {
            x: {
                value: x,
                writable: true
            },
            y: {
                value: y,
                writable: true
            }
        });

        return particle;
    }

    function drawParticle(p) {
        // Using the objects in the list, we draw particles to the canvas
        context.beginPath();
        context.fillStyle = "rgba(255, 255, 255, 0.4)";
        context.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, false);
        context.closePath();
        context.fill();
    }
    
    
    function animate(counter) {
        var p, i, test, index, chance, verMove;
        // Clear canvas
        context.clearRect(0, 0, canvas.width, canvas.height);

        // Create new particle
        
        
        chance = Math.random();
        if (particles.length < maxParticles && chance > 0.9) {
            particles.push(createParticle());
        }

        // Move all particles, and remove those on the far right.
        
        verMove = Math.random() - 0.5;
        for (i = 0; i < particles.length; i += 1) {
            p = particles[i];

            if (p.x > canvas.width) {
                index = particles.indexOf(p);
                particles.splice(index, 1);
            } else {
                p.y += (verMove * p.speed) / 10; // particle y moved up or down.
                p.x += p.speed; // particle x is updated by speed.
            }
        }

        // Redraw all particles
        for (i = 0; i < particles.length; i += 1) {
            drawParticle(particles[i]);
        }
    
    }
    
    window.setInterval(animate.bind(this, counter), 33);
};
    
    
