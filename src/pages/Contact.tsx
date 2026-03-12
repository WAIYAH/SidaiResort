import React, { useState } from 'react';
import { MapPin, Phone, Mail, Clock, Send } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { useToast } from '@/hooks/use-toast';
import { CheckCircle } from 'lucide-react';

const Contact = () => {
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    serviceInterest: '',
    message: '',
    preferredContact: 'email'
  });
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { toast } = useToast();

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSelectChange = (name: string, value: string) => {
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    await new Promise(resolve => setTimeout(resolve, 2000));
    toast({
      title: "Message Sent Successfully!",
      description: "Thank you for your interest in Sidai Resort. We'll get back to you within 24 hours.",
    });
    setFormData({
      firstName: '', lastName: '', email: '', phone: '',
      serviceInterest: '', message: '', preferredContact: 'email'
    });
    setIsSubmitting(false);
  };

  const contactInfo = [
    { icon: MapPin, title: 'Visit Us', details: ['Maasai Mara National Reserve', 'Narok County, Kenya', 'P.O. Box 12345-00100'] },
    { icon: Phone, title: 'Call Us', details: ['+254 700 123 456', '+254 733 987 654', 'Toll-free: 0800 SIDAI'] },
    { icon: Mail, title: 'Email Us', details: ['reservations@sidairesort.com', 'info@sidairesort.com', 'events@sidairesort.com'] },
    { icon: Clock, title: 'Hours', details: ['Reception: 24/7', 'Reservations: 8AM - 8PM EAT', 'Events: 9AM - 6PM EAT'] }
  ];

  const services = [
    'Accommodation Booking', 'Conference & Events', 'Wedding Planning',
    'Safari Packages', 'Spa & Wellness', 'Dining Reservations',
    'Group Bookings', 'Transportation', 'Other Inquiry'
  ];

  return (
    <div className="min-h-screen pt-14 sm:pt-16 lg:pt-20">
      {/* Hero Section */}
      <section className="relative h-52 sm:h-72 lg:h-96 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <h1 className="text-3xl sm:text-4xl md:text-6xl font-montserrat font-bold mb-3 sm:mb-6 animate-fade-in-up">
            Contact <span className="text-primary glow-effect">Sidai Resort</span>
          </h1>
          <p className="text-base sm:text-xl md:text-2xl font-playfair opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            Let's plan your perfect Kenya safari experience
          </p>
        </div>
      </section>

      {/* Contact Information */}
      <section className="py-12 sm:py-16 lg:py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8 mb-12 sm:mb-16">
            {contactInfo.map((info, index) => (
              <Card 
                key={index} 
                className="text-center glassmorphism hover:glow-effect transition-all duration-300 transform hover:scale-105"
              >
                <CardHeader className="pb-2 sm:pb-4">
                  <info.icon className="w-8 h-8 sm:w-12 sm:h-12 text-primary mx-auto mb-2 sm:mb-4" />
                  <CardTitle className="font-montserrat text-foreground text-sm sm:text-base">{info.title}</CardTitle>
                </CardHeader>
                <CardContent className="pt-0">
                  {info.details.map((detail, detailIndex) => (
                    <p key={detailIndex} className="text-muted-foreground mb-1 text-xs sm:text-sm break-words">
                      {detail}
                    </p>
                  ))}
                </CardContent>
              </Card>
            ))}
          </div>

          {/* Contact Form & Map */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 max-w-7xl mx-auto">
            {/* Contact Form */}
            <Card className="neumorphism">
              <CardHeader>
                <CardTitle className="text-2xl sm:text-3xl font-montserrat font-bold text-foreground">
                  Send Us a Message
                </CardTitle>
                <p className="text-muted-foreground text-sm sm:text-base">
                  Fill out the form below and we'll get back to you within 24 hours.
                </p>
              </CardHeader>
              <CardContent>
                <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <Label htmlFor="firstName" className="font-montserrat font-medium text-sm">First Name *</Label>
                      <Input id="firstName" name="firstName" value={formData.firstName} onChange={handleInputChange} required placeholder="Your first name" />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="lastName" className="font-montserrat font-medium text-sm">Last Name *</Label>
                      <Input id="lastName" name="lastName" value={formData.lastName} onChange={handleInputChange} required placeholder="Your last name" />
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <Label htmlFor="email" className="font-montserrat font-medium text-sm">Email *</Label>
                      <Input id="email" name="email" type="email" value={formData.email} onChange={handleInputChange} required placeholder="your@email.com" />
                    </div>
                    <div className="space-y-2">
                      <Label htmlFor="phone" className="font-montserrat font-medium text-sm">Phone</Label>
                      <Input id="phone" name="phone" type="tel" value={formData.phone} onChange={handleInputChange} placeholder="+254 700 123 456" />
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="serviceInterest" className="font-montserrat font-medium text-sm">Service Interest</Label>
                    <Select value={formData.serviceInterest} onValueChange={(value) => handleSelectChange('serviceInterest', value)}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select a service" />
                      </SelectTrigger>
                      <SelectContent>
                        {services.map((service) => (
                          <SelectItem key={service} value={service.toLowerCase().replace(/\s+/g, '-')}>
                            {service}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="preferredContact" className="font-montserrat font-medium text-sm">Preferred Contact</Label>
                    <Select value={formData.preferredContact} onValueChange={(value) => handleSelectChange('preferredContact', value)}>
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="email">Email</SelectItem>
                        <SelectItem value="phone">Phone Call</SelectItem>
                        <SelectItem value="whatsapp">WhatsApp</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="message" className="font-montserrat font-medium text-sm">Message *</Label>
                    <Textarea
                      id="message" name="message" value={formData.message} onChange={handleInputChange}
                      required rows={4} className="resize-none"
                      placeholder="Tell us about your planned visit..."
                    />
                  </div>

                  <Button 
                    type="submit" size="lg" 
                    className="w-full bg-gradient-sunset hover:glow-effect font-montserrat font-semibold disabled:opacity-70"
                    disabled={isSubmitting}
                  >
                    {isSubmitting ? (
                      <>
                        <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2" />
                        Sending...
                      </>
                    ) : (
                      <>
                        <Send className="w-5 h-5 mr-2" />
                        Send Message
                      </>
                    )}
                  </Button>
                </form>
              </CardContent>
            </Card>

            {/* Map & Location Info */}
            <div className="space-y-6 sm:space-y-8">
              <Card className="neumorphism overflow-hidden">
                <CardContent className="p-0">
                  <div className="h-56 sm:h-80 bg-gradient-earth relative flex items-center justify-center">
                    <div className="text-center text-foreground px-4">
                      <MapPin className="w-12 h-12 sm:w-16 sm:h-16 text-primary mx-auto mb-3 sm:mb-4 animate-float" />
                      <h3 className="text-lg sm:text-xl font-montserrat font-semibold mb-2">Find Us Here</h3>
                      <p className="text-muted-foreground text-sm sm:text-base">
                        Maasai Mara National Reserve<br />
                        Narok County, Kenya
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card className="glassmorphism">
                <CardHeader>
                  <CardTitle className="font-montserrat text-foreground flex items-center text-base sm:text-lg">
                    <CheckCircle className="w-5 h-5 sm:w-6 sm:h-6 text-secondary mr-2 sm:mr-3" />
                    Why Our Location?
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <ul className="space-y-2 sm:space-y-3 text-muted-foreground">
                    {[
                      'Prime location in the heart of Maasai Mara',
                      'Easy access to game drives and cultural experiences',
                      '2-hour drive from Nairobi (helicopter transfers available)',
                      'Stunning views of Mount Kilimanjaro',
                      'Close to authentic Maasai villages'
                    ].map((item, i) => (
                      <li key={i} className="flex items-start">
                        <CheckCircle className="w-4 h-4 sm:w-5 sm:h-5 text-secondary mr-2 sm:mr-3 mt-0.5 flex-shrink-0" />
                        <span className="text-sm sm:text-base">{item}</span>
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>

              <Card className="bg-gradient-sunset text-white">
                <CardContent className="p-4 sm:p-6 text-center">
                  <h3 className="text-xl sm:text-2xl font-montserrat font-bold mb-3 sm:mb-4">
                    Need Immediate Assistance?
                  </h3>
                  <p className="mb-4 sm:mb-6 opacity-90 text-sm sm:text-base">
                    Call our 24/7 guest services hotline for urgent inquiries.
                  </p>
                  <Button 
                    size="lg" variant="secondary"
                    className="font-montserrat font-semibold transform hover:scale-105 transition-all duration-300 text-sm sm:text-base"
                  >
                    <Phone className="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                    Call: +254 700 123 456
                  </Button>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Contact;
