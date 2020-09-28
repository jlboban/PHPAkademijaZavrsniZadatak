const particlesJSON = {
    "particles": {
        "number": {
            "value": 30,
            "density": {
                "enable": true,
                "value_area": 800
            }
        },
        "color": {
            "value": "#ff682b"
        },
        "shape": {
            "type": "polygon",
            "stroke": {
                "width": 1,
                "color": "#000000"
            },
            "polygon": {
                "nb_sides": 6
            },
            "image": {

            }
        },
        "opacity": {
            "value": 0.5,
            "random": true,
            "anim": {
                "enable": false,
                "speed": 1,
                "opacity_min": 0.1,
                "sync": false
            }
        },
        "size": {
            "value": 7,
            "random": false,
            "anim": {
                "enable": false,
                "speed": 40,
                "size_min": 3,
                "sync": false
            }
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ff5007",
            "opacity": 1,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 4,
            "direction": "top",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
            }
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {
                "enable": true,
                "mode": "grab"
            },
            "onclick": {
                "enable": false,
                "mode": "repulse"
            },
            "resize": true
        },
        "modes": {
            "grab": {
                "distance": 200,
                "line_linked": {
                    "opacity": 3
                }
            },
            "bubble": {
                "distance": 400,
                "size": 4,
                "duration": 2,
                "opacity": 8,
                "speed": 20
            },
            "repulse": {
                "distance": 200,
                "duration": 0.4
            },
            "push": {
                "particles_nb": 4
            },
            "remove": {
                "particles_nb": 2
            }
        }
    },
    "retina_detect": true
}

particlesJS("particles-js", particlesJSON);