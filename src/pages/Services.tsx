import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { 
  Users, 
  Waves, 
  UtensilsCrossed, 
  Flower, 
  Calendar, 
  Wifi, 
  Car, 
  TreePine,
  ChevronRight,
  CheckCircle
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import conferencingImage from '@/assets/conferencing.jpg';
import swimmingImage from '@/assets/swimming-pool.jpg';
import diningImage from '@/assets/dining.jpg';
import spaImage from '@/assets/spa-wellness.jpg';

const Services = () => {
  const [activeTab, setActiveTab] = useState('conferencing');

  const services = {
    conferencing: {
      title: 'World-Class Conferencing',
      subtitle: 'State-of-the-art facilities for memorable corporate events',
      image: conferencingImage,
      icon: Users,
      features: [
        'Multiple conference halls (50-500 capacity)',
        'Ultra-modern AV equipment and tech support',
        'High-speed WiFi throughout the facility',
        'Stunning savanna views from all meeting rooms',
        'Professional catering services',
        'Team building activities and safari breaks',
        'Dedicated event coordinators',
        'Breakout rooms for smaller sessions'
      ],
      description: 'Transform your corporate events into inspiring experiences. Our conference facilities blend cutting-edge technology with breathtaking natural beauty, creating the perfect environment for productive meetings, strategic planning, and team building.',
      pricing: 'Starting from $150 per person per day (full package including meals and activities)'
    },
    swimming: {
      title: 'Infinity Pool Paradise',
      subtitle: 'Luxurious aquatic experiences with panoramic views',
      image: swimmingImage,
      icon: Waves,
      features: [
        'Three infinity pools with Kilimanjaro views',
        'Heated pools available year-round',
        'Pool-side bar and restaurant service',
        'Private cabanas and sun loungers',
        'Kids pool with safety features',
        'Swimming lessons and aqua fitness classes',
        'Sunset pool parties and events',
        'Professional lifeguards on duty'
      ],
      description: 'Dive into luxury with our stunning infinity pools that seem to merge with the endless African horizon. Whether you\'re seeking relaxation or recreation, our aquatic facilities offer the perfect escape.',
      pricing: 'Included in all accommodation packages. Day passes available for $50 per person'
    },
    dining: {
      title: 'Authentic Maasai Cuisine',
      subtitle: 'A culinary journey through Kenya and beyond',
      image: diningImage,
      icon: UtensilsCrossed,
      features: [
        'Three specialty restaurants (Maasai, International, Fine Dining)',
        'Farm-to-table fresh ingredients',
        'Traditional Maasai cooking classes',
        'Wine cellar with premium selections',
        'Private dining experiences under the stars',
        'Dietary restrictions and allergies accommodated',
        'Bush breakfast and sundowner experiences',
        'Cultural dining ceremonies'
      ],
      description: 'Embark on a gastronomic adventure that celebrates authentic Maasai flavors alongside international cuisines. Our expert chefs use locally sourced ingredients to create unforgettable dining experiences.',
      pricing: 'À la carte dining from $25-85 per person. Full board packages available'
    },
    wellness: {
      title: 'Wellness & Spa Sanctuary',
      subtitle: 'Traditional therapies meet modern wellness',
      image: spaImage,
      icon: Flower,
      features: [
        'Traditional Maasai healing treatments',
        'Full-service spa with 8 treatment rooms',
        'Yoga and meditation pavilion',
        'Fitness center with personal trainers',
        'Wellness consultations and programs',
        'Herbal medicine workshops',
        'Couples massage and romantic packages',
        'Detox and rejuvenation retreats'
      ],
      description: 'Restore your mind, body, and spirit with our comprehensive wellness offerings. Combining ancient Maasai healing wisdom with modern spa treatments for the ultimate rejuvenation experience.',
      pricing: 'Spa treatments from $80-250. Wellness packages from $200 per day'
    }
  };

  const additionalServices = [
    {
      icon: TreePine,
      title: 'Safari Adventures',
      description: 'Guided game drives, walking safaris, and cultural village visits'
    },
    {
      icon: Calendar,
      title: 'Event Planning',
      description: 'Weddings, celebrations, and special occasions coordination'
    },
    {
      icon: Car,
      title: 'Airport Transfers',
      description: 'Luxury transportation to and from all major airports'
    },
    {
      icon: Wifi,
      title: 'Connectivity',
      description: 'High-speed internet and business center facilities'
    }
  ];

  return (
    <div className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="relative h-96 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <h1 className="text-4xl md:text-6xl font-montserrat font-bold mb-6 animate-fade-in-up">
            Our <span className="text-primary glow-effect">Sidai</span> Services
          </h1>
          <p className="text-xl md:text-2xl font-playfair opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            Discover world-class amenities and authentic experiences
          </p>
        </div>
      </section>

      {/* Main Services Section */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4">
          <Tabs value={activeTab} onValueChange={setActiveTab} className="max-w-7xl mx-auto">
            <TabsList className="grid w-full grid-cols-2 lg:grid-cols-4 mb-12 bg-muted/50 rounded-xl p-2">
              <TabsTrigger 
                value="conferencing" 
                className="flex items-center space-x-2 font-montserrat font-medium data-[state=active]:bg-primary data-[state=active]:text-primary-foreground"
              >
                <Users className="w-4 h-4" />
                <span className="hidden sm:inline">Conferencing</span>
              </TabsTrigger>
              <TabsTrigger 
                value="swimming" 
                className="flex items-center space-x-2 font-montserrat font-medium data-[state=active]:bg-safari-blue data-[state=active]:text-white"
              >
                <Waves className="w-4 h-4" />
                <span className="hidden sm:inline">Swimming</span>
              </TabsTrigger>
              <TabsTrigger 
                value="dining" 
                className="flex items-center space-x-2 font-montserrat font-medium data-[state=active]:bg-accent data-[state=active]:text-accent-foreground"
              >
                <UtensilsCrossed className="w-4 h-4" />
                <span className="hidden sm:inline">Dining</span>
              </TabsTrigger>
              <TabsTrigger 
                value="wellness" 
                className="flex items-center space-x-2 font-montserrat font-medium data-[state=active]:bg-secondary data-[state=active]:text-secondary-foreground"
              >
                <Flower className="w-4 h-4" />
                <span className="hidden sm:inline">Wellness</span>
              </TabsTrigger>
            </TabsList>

            {Object.entries(services).map(([key, service]) => (
              <TabsContent key={key} value={key} className="mt-0">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                  <div className="order-2 lg:order-1">
                    <div className="flex items-center mb-6">
                      <service.icon className="w-8 h-8 text-primary mr-4" />
                      <div>
                        <h2 className="text-3xl md:text-4xl font-montserrat font-bold text-foreground">
                          {service.title}
                        </h2>
                        <p className="text-lg text-muted-foreground font-playfair">
                          {service.subtitle}
                        </p>
                      </div>
                    </div>

                    <p className="text-lg leading-relaxed mb-8 text-foreground">
                      {service.description}
                    </p>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                      {service.features.map((feature, index) => (
                        <div key={index} className="flex items-start space-x-3">
                          <CheckCircle className="w-5 h-5 text-secondary flex-shrink-0 mt-0.5" />
                          <span className="text-foreground">{feature}</span>
                        </div>
                      ))}
                    </div>

                    <div className="bg-muted/50 rounded-lg p-6 mb-8">
                      <h4 className="font-montserrat font-semibold text-foreground mb-2">Pricing</h4>
                      <p className="text-muted-foreground">{service.pricing}</p>
                    </div>

                    <Button 
                      size="lg" 
                      className="bg-gradient-sunset hover:glow-effect font-montserrat font-semibold transform hover:scale-105 transition-all duration-300"
                    >
                      Book This Experience
                      <ChevronRight className="ml-2 w-5 h-5" />
                    </Button>
                  </div>

                  <div className="order-1 lg:order-2">
                    <div className="relative rounded-2xl overflow-hidden neumorphism">
                      <img 
                        src={service.image} 
                        alt={service.title}
                        className="w-full h-96 lg:h-full object-cover"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />
                    </div>
                  </div>
                </div>
              </TabsContent>
            ))}
          </Tabs>
        </div>
      </section>

      {/* Additional Services */}
      <section className="py-20 bg-gradient-earth">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-montserrat font-bold text-foreground mb-6">
              Additional <span className="text-secondary">Experiences</span>
            </h2>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
              Complete your Sidai journey with our comprehensive range of complementary services.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            {additionalServices.map((service, index) => (
              <Card 
                key={index} 
                className="text-center glassmorphism hover:glow-effect transition-all duration-300 transform hover:scale-105"
              >
                <CardHeader>
                  <service.icon className="w-12 h-12 text-primary mx-auto mb-4" />
                  <CardTitle className="font-montserrat text-foreground">{service.title}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="text-muted-foreground">{service.description}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gradient-sunset">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-4xl md:text-5xl font-montserrat font-bold text-white mb-6">
            Ready to Experience <span className="text-primary glow-effect">Sidai</span>?
          </h2>
          <p className="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Contact our team to customize your perfect safari resort experience.
          </p>
          <div className="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
            <Link to="/contact">
              <Button 
                size="lg" 
                variant="secondary"
                className="font-montserrat font-bold px-8 py-4 text-lg transform hover:scale-105 transition-all duration-300"
              >
                Contact Us Today
              </Button>
            </Link>
            <Link to="/">
              <Button 
                size="lg" 
                variant="outline"
                className="border-white text-white hover:bg-white hover:text-foreground font-montserrat font-semibold px-8 py-4 text-lg"
              >
                Return Home
              </Button>
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Services;