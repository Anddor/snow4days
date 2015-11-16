function createParticle(context, canvas) {
    "use strict";
    // Each "particle" is created as an object containing x and y pos, a radius, and a horisontal speed.
    
    var x, y, radius, speed, particle;
    x = 0;
    y = (Math.random()) * canvas.height;
    radius = Math.random() * 5;
    speed = Math.random();
    
    particle = {radius: radius, speed: speed};
    Object.defineProperties(particle, {
        _x: {
            value: 0,
            writable: true
        },
        _y: {
            value: 10,
            writable: true
        },
        x: {
            get: function () {
                return this._x;
            },
            set: function (newValue) {
                this._x = newValue;
            }
            
        },
        y: {
            get: function () {
                return this._y;
            },
            set: function (newValue) {
                this._y = newValue;
            }
            
        }
    });
        
    return particle;
}
function drawParticle(p, context) {
    "use strict";
    
    // Using the objects in the list, we draw particles to the canvas
    context.beginPath();
    context.fillStyle = "rgba(255, 255, 255, 0.3)";
    context.arc(p.x, p.y, p.radius, 0, 0.5 * Math.PI, false);
    context.closePath();
    context.fill();
}


function animate(context, canvas, particles) {
    "use strict";
    var p;
    // Clear canvas
    context.clearRect(0, 0, canvas.width, canvas.height);
    
    // Create new particle
    particles.push(createParticle(context, canvas));

    // Move all particles, and remove those on the far right.
    for (var i; i < particles.length; i++) {
        p = particles[i];
        
        if (p.x > canvas.width) {
            particles.pop(p); // particles at far right is removed.
        } else {
            p.y += (Math.random() - 0.5) /10; // particle y moved up or down.
            p.x += p.speed; // particle x is updated by speed.
        }
    }
    
    
    // Redraw all particles
    for (p in particles) {
        drawParticle(p, context);
    }
}


window.onload = function () {
    "use strict";
    var particles = [];
    
    var canvas = document.getElementById("particle-canvas");
    var context = canvas.getContext("2d");
    
    window.setInterval(animate(context, canvas, particles), 50);
    
};
