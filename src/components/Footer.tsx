import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { Facebook, Instagram, Twitter, Youtube, Mail, Phone, MapPin, Send, ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';

const Footer = () => {
  const [email, setEmail] = useState('');
  const { toast } = useToast();

  const handleNewsletterSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email) return;
    toast({
      title: 'Subscribed!',
      description: 'Thank you for subscribing to our newsletter.',
    });
    setEmail('');
  };

  const quickLinks = [
    { to: '/', label: 'Home' },
    { to: '/services', label: 'Services' },
    { to: '/gallery', label: 'Gallery' },
    { to: '/contact', label: 'Contact Us' },
  ];

  const legalLinks = [
    { to: '/privacy-policy', label: 'Privacy Policy' },
    { to: '/terms-of-service', label: 'Terms of Service' },
    { to: '/cookie-policy', label: 'Cookie Policy' },
  ];

  const socialLinks = [
    { icon: Facebook, label: 'Facebook', href: '#' },
    { icon: Instagram, label: 'Instagram', href: '#' },
    { icon: Twitter, label: 'Twitter', href: '#' },
    { icon: Youtube, label: 'YouTube', href: '#' },
  ];

  return (
    <footer className="bg-gradient-savanna text-white relative overflow-hidden">
      {/* Subtle pattern overlay */}
      <div className="absolute inset-0 opacity-5 pointer-events-none" style={{
        backgroundImage: 'repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,0.03) 20px, rgba(255,255,255,0.03) 40px)'
      }} />

      {/* Newsletter Banner */}
      <div className="relative border-b border-white/10">
        <div className="container mx-auto px-4 py-10 sm:py-12">
          <div className="max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-6 md:gap-10">
            <div className="flex-1 text-center md:text-left">
              <h3 className="text-xl sm:text-2xl font-montserrat font-bold text-primary mb-2">
                Stay Connected with Sidai
              </h3>
              <p className="text-sm sm:text-base text-white/80">
                Get exclusive offers, travel tips, and updates from the heart of Kenya.
              </p>
            </div>
            <form onSubmit={handleNewsletterSubmit} className="w-full md:w-auto flex gap-2">
              <Input
                type="email"
                placeholder="Enter your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="bg-white/10 border-white/20 text-white placeholder:text-white/50 focus:border-primary focus:ring-primary min-w-0 flex-1 md:w-64"
                aria-label="Email for newsletter"
              />
              <Button
                type="submit"
                className="bg-primary text-primary-foreground hover:bg-primary/90 font-montserrat font-semibold px-5 shrink-0"
              >
                <Send className="w-4 h-4 sm:mr-2" />
                <span className="hidden sm:inline">Subscribe</span>
              </Button>
            </form>
          </div>
        </div>
      </div>

      {/* Main Footer Content */}
      <div className="relative container mx-auto px-4 py-10 sm:py-14">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
          {/* Brand */}
          <div className="sm:col-span-2 lg:col-span-1">
            <Link to="/" className="inline-flex items-center gap-2 group mb-4">
              <div className="w-9 h-9 rounded-full bg-gradient-sunset flex items-center justify-center">
                <span className="text-sm font-montserrat font-bold text-primary-foreground">S</span>
              </div>
              <span className="text-xl font-montserrat font-bold text-primary">Sidai Resort</span>
            </Link>
            <p className="text-sm text-white/80 leading-relaxed mb-5 max-w-xs">
              Where 'Good' meets luxury in the heart of Kenya. Experience world-class hospitality 
              with authentic Maasai culture and breathtaking landscapes.
            </p>
            {/* Social Icons */}
            <div className="flex gap-3">
              {socialLinks.map(({ icon: Icon, label, href }) => (
                <a
                  key={label}
                  href={href}
                  className="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary hover:scale-110 transition-all duration-300"
                  aria-label={label}
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <Icon className="w-4 h-4" />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-sm font-montserrat font-bold uppercase tracking-wider text-primary mb-4">
              Quick Links
            </h4>
            <ul className="space-y-2.5">
              {quickLinks.map(({ to, label }) => (
                <li key={to}>
                  <Link
                    to={to}
                    className="group inline-flex items-center gap-1.5 text-sm text-white/80 hover:text-primary transition-colors duration-300"
                  >
                    <ArrowRight className="w-3 h-3 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" />
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Legal */}
          <div>
            <h4 className="text-sm font-montserrat font-bold uppercase tracking-wider text-primary mb-4">
              Legal
            </h4>
            <ul className="space-y-2.5">
              {legalLinks.map(({ to, label }) => (
                <li key={to}>
                  <Link
                    to={to}
                    className="group inline-flex items-center gap-1.5 text-sm text-white/80 hover:text-primary transition-colors duration-300"
                  >
                    <ArrowRight className="w-3 h-3 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" />
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h4 className="text-sm font-montserrat font-bold uppercase tracking-wider text-primary mb-4">
              Contact Info
            </h4>
            <ul className="space-y-3">
              <li className="flex items-start gap-3 text-sm text-white/80">
                <MapPin className="w-4 h-4 text-primary mt-0.5 shrink-0" />
                <span>Naroosura, Narok County<br />Kenya &bull; P.O. Box 12345</span>
              </li>
              <li>
                <a href="tel:+254700123456" className="flex items-center gap-3 text-sm text-white/80 hover:text-primary transition-colors duration-300">
                  <Phone className="w-4 h-4 text-primary shrink-0" />
                  +254 700 123 456
                </a>
              </li>
              <li>
                <a href="mailto:hello@sidairesort.com" className="flex items-center gap-3 text-sm text-white/80 hover:text-primary transition-colors duration-300">
                  <Mail className="w-4 h-4 text-primary shrink-0" />
                  hello@sidairesort.com
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="relative border-t border-white/10">
        <div className="container mx-auto px-4 py-5">
          <div className="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-white/60">
            <p>© {new Date().getFullYear()} Sidai Resort. All rights reserved.</p>
            <p className="font-montserrat">
              Crafted with ❤️ in Kenya
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
