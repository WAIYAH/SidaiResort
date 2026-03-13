import React, { useEffect } from 'react';
import { Link } from 'react-router-dom';
import { ChevronRight, Star, Users, Award, Leaf } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import heroImage from '@/assets/hero-sunset.jpg';
import conferencingImage from '@/assets/conferencing.jpg';
import swimmingImage from '@/assets/swimming-pool.jpg';
import diningImage from '@/assets/dining.jpg';
import spaImage from '@/assets/spa-wellness.jpg';

const Home = () => {
  useEffect(() => {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in-up');
        }
      });
    }, observerOptions);

    const elements = document.querySelectorAll('.animate-on-scroll');
    elements.forEach((el) => observer.observe(el));

    return () => {
      elements.forEach((el) => observer.unobserve(el));
    };
  }, []);

  const services = [
    {
      title: 'World-Class Conferencing',
      description: 'State-of-the-art meeting halls with stunning savanna views for unforgettable corporate events.',
      image: conferencingImage,
      link: '/services#conferencing'
    },
    {
      title: 'Infinity Pool Paradise',
      description: 'Luxurious swimming pools with breathtaking views of Mount Kilimanjaro and endless horizons.',
      image: swimmingImage,
      link: '/services#swimming'
    },
    {
      title: 'Authentic Maasai Cuisine',
      description: 'Savor traditional flavors and international delights crafted by our world-class chefs.',
      image: diningImage,
      link: '/services#dining'
    },
    {
      title: 'Wellness & Spa Sanctuary',
      description: 'Rejuvenate your spirit with traditional therapies and modern wellness treatments.',
      image: spaImage,
      link: '/services#spa'
    }
  ];

  const testimonials = [
    {
      name: 'Sarah Johnson',
      rating: 5,
      text: 'Sidai Resort exceeded all expectations! The Maasai cultural experience was transformative, and the hospitality was beyond compare.',
      location: 'New York, USA'
    },
    {
      name: 'David Mbeki',
      rating: 5,
      text: 'Perfect venue for our corporate retreat. The conference facilities are world-class, and the natural beauty inspired creativity.',
      location: 'Nairobi, Kenya'
    },
    {
      name: 'Emma Thompson',
      rating: 5,
      text: 'A magical safari experience combined with luxury beyond imagination. The sunset views from our room were unforgettable.',
      location: 'London, UK'
    }
  ];

  const stats = [
    { icon: Users, number: '10,000+', label: 'Happy Guests' },
    { icon: Award, number: '50+', label: 'Awards Won' },
    { icon: Leaf, number: '100%', label: 'Eco-Friendly' },
    { icon: Star, number: '5-Star', label: 'Luxury Rating' }
  ];

  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section 
        className="relative min-h-[100svh] flex items-center justify-center bg-cover bg-center bg-no-repeat maasai-pattern"
        style={{ backgroundImage: `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.2)), url(${heroImage})` }}
      >
        <div className="text-center text-white z-10 max-w-4xl px-4 sm:px-6 py-20">
          <h1 className="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-montserrat font-bold mb-4 sm:mb-6 animate-fade-in-up">
            Welcome to{' '}
            <span className="text-primary glow-effect">Sidai Resort</span>
          </h1>
          <p className="text-lg sm:text-xl md:text-2xl lg:text-3xl font-playfair mb-4 sm:mb-8 animate-fade-in-up opacity-90" style={{ animationDelay: '0.3s' }}>
            Where 'Good' Meets Luxury
          </p>
          <p className="text-base sm:text-lg md:text-xl mb-8 sm:mb-12 max-w-2xl mx-auto animate-fade-in-up" style={{ animationDelay: '0.6s' }}>
            Experience world-class hospitality in the heart of Kenya, where authentic Maasai culture meets modern luxury.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style={{ animationDelay: '0.9s' }}>
            <Button 
              size="lg" 
              className="bg-gradient-sunset hover:glow-effect font-montserrat font-bold px-6 sm:px-8 py-4 text-base sm:text-lg transform hover:scale-105 transition-all duration-300"
            >
              Book Your Stay
              <ChevronRight className="ml-2 w-5 h-5" />
            </Button>
            <Link to="/services">
              <Button 
                variant="secondary"
                size="lg" 
                className="w-full h-full bg-white/95 text-foreground hover:bg-white hover:glow-effect font-montserrat font-semibold px-6 sm:px-8 py-4 text-base sm:text-lg transform hover:scale-105 transition-all duration-300 rounded-md"
              >
                Explore Services
              </Button>
            </Link>
          </div>
        </div>
        
        {/* Floating particles - hidden on small screens for performance */}
        <div className="absolute inset-0 overflow-hidden pointer-events-none hidden sm:block">
          {[...Array(6)].map((_, i) => (
            <div
              key={i}
              className="absolute w-2 h-2 bg-primary/30 rounded-full animate-float"
              style={{
                left: `${15 + i * 15}%`,
                top: `${20 + i * 10}%`,
                animationDelay: `${i * 0.5}s`,
                animationDuration: `${3 + i * 0.3}s`
              }}
            />
          ))}
        </div>
      </section>

      {/* About Section */}
      <section className="py-12 sm:py-16 lg:py-20 bg-gradient-earth">
        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto">
            <div className="text-center mb-10 sm:mb-16 animate-on-scroll">
              <h2 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold text-foreground mb-4 sm:mb-6">
                The Spirit of <span className="text-primary">Sidai</span>
              </h2>
              <p className="text-base sm:text-xl text-muted-foreground max-w-3xl mx-auto leading-relaxed">
                In the Maasai language, "Sidai" means "good" - and goodness is woven into every aspect of our resort. 
                From the breathtaking landscapes to our warm hospitality, we create experiences that touch the soul.
              </p>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center mb-16">
              <div className="animate-on-scroll">
                <h3 className="text-2xl sm:text-3xl font-montserrat font-semibold text-secondary mb-4 sm:mb-6">
                  Where Culture Meets Luxury
                </h3>
                <p className="text-base sm:text-lg leading-relaxed mb-4 sm:mb-6">
                  Nestled in the heart of Kenya's magnificent landscape, Sidai Resort offers an authentic safari 
                  experience infused with Maasai culture and modern luxury. Our eco-friendly approach ensures 
                  that your stay contributes to conservation and community development.
                </p>
                <p className="text-base sm:text-lg leading-relaxed">
                  Every corner of our resort tells a story - from traditional Maasai architecture to contemporary 
                  amenities that exceed international standards. Experience the warmth of Kenyan hospitality 
                  while enjoying world-class facilities.
                </p>
              </div>

              <div className="grid grid-cols-2 gap-4 sm:gap-6 animate-on-scroll">
                {stats.map((stat, index) => (
                  <Card key={index} className="glassmorphism hover:glow-effect transition-all duration-300 transform hover:scale-105">
                    <CardContent className="p-4 sm:p-6 text-center">
                      <stat.icon className="w-6 h-6 sm:w-8 sm:h-8 text-primary mx-auto mb-2 sm:mb-3" />
                      <div className="text-lg sm:text-2xl font-montserrat font-bold text-foreground">{stat.number}</div>
                      <div className="text-xs sm:text-sm text-muted-foreground">{stat.label}</div>
                    </CardContent>
                  </Card>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Services Teaser Section */}
      <section className="py-12 sm:py-16 lg:py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="text-center mb-10 sm:mb-16 animate-on-scroll">
            <h2 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold text-foreground mb-4 sm:mb-6">
              Our <span className="text-secondary">Signature</span> Experiences
            </h2>
            <p className="text-base sm:text-xl text-muted-foreground max-w-3xl mx-auto">
              Discover a world of luxury amenities and authentic experiences designed to create unforgettable memories.
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            {services.map((service, index) => (
              <Card 
                key={index} 
                className="group overflow-hidden neumorphism hover:glow-effect transition-all duration-500 transform hover:scale-105 animate-on-scroll"
                style={{ animationDelay: `${index * 0.2}s` }}
              >
                <div className="relative overflow-hidden">
                  <img 
                    src={service.image} 
                    alt={service.title}
                    className="w-full h-44 sm:h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                    loading="lazy"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                </div>
                <CardContent className="p-4 sm:p-6">
                  <h3 className="text-lg sm:text-xl font-montserrat font-semibold text-foreground mb-2 sm:mb-3 group-hover:text-primary transition-colors duration-300">
                    {service.title}
                  </h3>
                  <p className="text-sm sm:text-base text-muted-foreground mb-3 sm:mb-4 line-clamp-3">
                    {service.description}
                  </p>
                  <Link 
                    to={service.link}
                    className="inline-flex items-center text-secondary hover:text-primary font-montserrat font-medium transition-colors duration-300 text-sm sm:text-base"
                  >
                    Learn More <ChevronRight className="ml-1 w-4 h-4" />
                  </Link>
                </CardContent>
              </Card>
            ))}
          </div>

          <div className="text-center mt-8 sm:mt-12 animate-on-scroll">
            <Link to="/services">
              <Button 
                size="lg" 
                variant="outline"
                className="border-secondary text-secondary hover:bg-secondary hover:text-secondary-foreground font-montserrat font-semibold px-6 sm:px-8 py-4"
              >
                View All Services
              </Button>
            </Link>
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="py-12 sm:py-16 lg:py-20 bg-gradient-savanna">
        <div className="container mx-auto px-4">
          <div className="text-center mb-10 sm:mb-16">
            <h2 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold text-white mb-4 sm:mb-6">
              Guest <span className="text-primary">Stories</span>
            </h2>
            <p className="text-base sm:text-xl text-white/90 max-w-3xl mx-auto">
              Hear from our guests about their unforgettable experiences at Sidai Resort.
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto">
            {testimonials.map((testimonial, index) => (
              <Card 
                key={index} 
                className="glassmorphism text-center transform hover:scale-105 transition-all duration-300 animate-on-scroll"
                style={{ animationDelay: `${index * 0.2}s` }}
              >
                <CardContent className="p-6 sm:p-8">
                  <div className="flex justify-center mb-3 sm:mb-4">
                    {[...Array(testimonial.rating)].map((_, i) => (
                      <Star key={i} className="w-4 h-4 sm:w-5 sm:h-5 text-primary fill-current" />
                    ))}
                  </div>
                  <p className="text-white/90 italic mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
                    "{testimonial.text}"
                  </p>
                  <div>
                    <div className="font-montserrat font-semibold text-white text-sm sm:text-base">{testimonial.name}</div>
                    <div className="text-primary text-xs sm:text-sm">{testimonial.location}</div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-12 sm:py-16 lg:py-20 bg-gradient-sunset">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold text-white mb-4 sm:mb-6">
            Ready for Sidai Goodness?
          </h2>
          <p className="text-base sm:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
            Start planning your unforgettable Kenya safari experience with authentic Maasai hospitality.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link to="/contact">
              <Button 
                size="lg" 
                variant="secondary"
                className="w-full sm:w-auto font-montserrat font-bold px-6 sm:px-8 py-4 text-base sm:text-lg transform hover:scale-105 transition-all duration-300"
              >
                Contact Us Today
              </Button>
            </Link>
            <Button 
              size="lg" 
              variant="outline"
              className="border-white text-white hover:bg-white hover:text-foreground font-montserrat font-semibold px-6 sm:px-8 py-4 text-base sm:text-lg"
            >
              View Gallery
            </Button>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
