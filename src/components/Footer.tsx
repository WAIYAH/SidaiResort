import React from 'react';
import { Link } from 'react-router-dom';
import { Facebook, Instagram, Twitter, Mail, Phone, MapPin } from 'lucide-react';

const Footer = () => {
  return (
    <footer className="bg-gradient-savanna text-white">
      <div className="container mx-auto px-4 py-10 sm:py-16">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8 sm:mb-12">
          {/* Brand & Description */}
          <div className="sm:col-span-2">
            <div className="text-2xl sm:text-3xl font-montserrat font-bold text-primary mb-3 sm:mb-4">
              Sidai Resort
            </div>
            <p className="text-sm sm:text-lg mb-4 sm:mb-6 leading-relaxed">
              Where 'Good' meets luxury in the heart of Kenya. Experience world-class hospitality 
              with authentic Maasai culture, breathtaking landscapes, and unforgettable safari adventures.
            </p>
            <div className="flex space-x-3 sm:space-x-4">
              {[
                { icon: Facebook, label: 'Facebook' },
                { icon: Instagram, label: 'Instagram' },
                { icon: Twitter, label: 'Twitter' },
              ].map(({ icon: Icon, label }) => (
                <a 
                  key={label}
                  href="#" 
                  className="p-2.5 sm:p-3 rounded-full bg-primary/20 hover:bg-primary hover:glow-effect transition-all duration-300 transform hover:scale-110"
                  aria-label={label}
                >
                  <Icon className="w-4 h-4 sm:w-5 sm:h-5" />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg sm:text-xl font-montserrat font-semibold mb-3 sm:mb-4 text-primary">Quick Links</h3>
            <ul className="space-y-2 text-sm sm:text-base">
              {[
                { to: '/', label: 'Home' },
                { to: '/services', label: 'Our Services' },
                { to: '/gallery', label: 'Gallery' },
                { to: '/contact', label: 'Contact Us' },
              ].map(({ to, label }) => (
                <li key={to}>
                  <Link to={to} className="hover:text-primary transition-colors duration-300">{label}</Link>
                </li>
              ))}
            </ul>

            <h3 className="text-lg sm:text-xl font-montserrat font-semibold mb-3 mt-6 text-primary">Legal</h3>
            <ul className="space-y-2 text-sm sm:text-base">
              {[
                { to: '/privacy-policy', label: 'Privacy Policy' },
                { to: '/terms-of-service', label: 'Terms of Service' },
                { to: '/cookie-policy', label: 'Cookie Policy' },
              ].map(({ to, label }) => (
                <li key={to}>
                  <Link to={to} className="hover:text-primary transition-colors duration-300">{label}</Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Information */}
          <div>
            <h3 className="text-lg sm:text-xl font-montserrat font-semibold mb-3 sm:mb-4 text-primary">Contact Info</h3>
            <ul className="space-y-3 sm:space-y-4 text-sm sm:text-base">
              <li className="flex items-start space-x-2 sm:space-x-3">
                <MapPin className="w-4 h-4 sm:w-5 sm:h-5 text-primary mt-1 flex-shrink-0" />
                <span>Naroosura, Narok County<br />Kenya &bull; P.O. Box 12345</span>
              </li>
              <li className="flex items-center space-x-2 sm:space-x-3">
                <Phone className="w-4 h-4 sm:w-5 sm:h-5 text-primary flex-shrink-0" />
                <span>+254 700 123 456</span>
              </li>
              <li className="flex items-center space-x-2 sm:space-x-3">
                <Mail className="w-4 h-4 sm:w-5 sm:h-5 text-primary flex-shrink-0" />
                <span className="break-all">hello@sidairesort.com</span>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-white/20 pt-6 sm:pt-8">
          <div className="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p className="text-xs sm:text-sm text-white/80">
              © {new Date().getFullYear()} Sidai Resort. All rights reserved.
            </p>
            <div className="flex flex-wrap justify-center gap-4 sm:gap-6 text-xs sm:text-sm text-white/80">
              <Link to="/privacy-policy" className="hover:text-primary transition-colors duration-300">Privacy Policy</Link>
              <Link to="/terms-of-service" className="hover:text-primary transition-colors duration-300">Terms of Service</Link>
              <Link to="/cookie-policy" className="hover:text-primary transition-colors duration-300">Cookie Policy</Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
