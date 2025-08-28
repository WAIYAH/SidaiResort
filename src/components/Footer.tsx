import React from 'react';
import { Link } from 'react-router-dom';
import { Facebook, Instagram, Twitter, Mail, Phone, MapPin } from 'lucide-react';

const Footer = () => {
  return (
    <footer className="bg-gradient-savanna text-white mt-20">
      <div className="container mx-auto px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          {/* Brand & Description */}
          <div className="lg:col-span-2">
            <div className="text-3xl font-montserrat font-bold text-primary mb-4">
              Sidai Resort
            </div>
            <p className="text-lg mb-6 leading-relaxed">
              Where 'Good' meets luxury in the heart of Kenya. Experience world-class hospitality 
              with authentic Maasai culture, breathtaking landscapes, and unforgettable safari adventures.
            </p>
            <div className="flex space-x-4">
              <a 
                href="#" 
                className="p-3 rounded-full bg-primary/20 hover:bg-primary hover:glow-effect transition-all duration-300 transform hover:scale-110"
                aria-label="Facebook"
              >
                <Facebook className="w-5 h-5" />
              </a>
              <a 
                href="#" 
                className="p-3 rounded-full bg-primary/20 hover:bg-primary hover:glow-effect transition-all duration-300 transform hover:scale-110"
                aria-label="Instagram"
              >
                <Instagram className="w-5 h-5" />
              </a>
              <a 
                href="#" 
                className="p-3 rounded-full bg-primary/20 hover:bg-primary hover:glow-effect transition-all duration-300 transform hover:scale-110"
                aria-label="Twitter"
              >
                <Twitter className="w-5 h-5" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-xl font-montserrat font-semibold mb-4 text-primary">Quick Links</h3>
            <ul className="space-y-2">
              <li>
                <Link 
                  to="/" 
                  className="hover:text-primary transition-colors duration-300"
                >
                  Home
                </Link>
              </li>
              <li>
                <Link 
                  to="/services" 
                  className="hover:text-primary transition-colors duration-300"
                >
                  Our Services
                </Link>
              </li>
              <li>
                <Link 
                  to="/contact" 
                  className="hover:text-primary transition-colors duration-300"
                >
                  Contact Us
                </Link>
              </li>
              <li>
                <a 
                  href="#" 
                  className="hover:text-primary transition-colors duration-300"
                >
                  Gallery
                </a>
              </li>
            </ul>
          </div>

          {/* Contact Information */}
          <div>
            <h3 className="text-xl font-montserrat font-semibold mb-4 text-primary">Contact Info</h3>
            <ul className="space-y-4">
              <li className="flex items-start space-x-3">
                <MapPin className="w-5 h-5 text-primary mt-1 flex-shrink-0" />
                <span>Maasai Mara, Kenya<br />P.O. Box 12345</span>
              </li>
              <li className="flex items-center space-x-3">
                <Phone className="w-5 h-5 text-primary flex-shrink-0" />
                <span>+254 700 123 456</span>
              </li>
              <li className="flex items-center space-x-3">
                <Mail className="w-5 h-5 text-primary flex-shrink-0" />
                <span>hello@sidairesort.com</span>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-white/20 pt-8">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <p className="text-sm text-white/80 mb-4 md:mb-0">
              © 2024 Sidai Resort. All rights reserved.
            </p>
            <div className="flex space-x-6 text-sm text-white/80">
              <a href="#" className="hover:text-primary transition-colors duration-300">
                Privacy Policy
              </a>
              <a href="#" className="hover:text-primary transition-colors duration-300">
                Terms of Service
              </a>
              <a href="#" className="hover:text-primary transition-colors duration-300">
                Cookie Policy
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;