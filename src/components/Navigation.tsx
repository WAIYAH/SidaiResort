import React, { useState, useEffect, useCallback } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, ChevronRight } from 'lucide-react';
import { Button } from '@/components/ui/button';

const Navigation = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const location = useLocation();

  // Close mobile menu on route change
  useEffect(() => {
    setIsMobileMenuOpen(false);
  }, [location.pathname]);

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Prevent body scroll when mobile menu is open
  useEffect(() => {
    document.body.style.overflow = isMobileMenuOpen ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [isMobileMenuOpen]);

  const closeMobileMenu = useCallback(() => setIsMobileMenuOpen(false), []);

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Services', path: '/services' },
    { name: 'Gallery', path: '/gallery' },
    { name: 'Contact', path: '/contact' },
  ];

  const isActive = (path: string) => location.pathname === path;

  return (
    <>
      <nav
        className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
          isScrolled
            ? 'bg-card/95 backdrop-blur-xl shadow-lg border-b border-border/50'
            : 'bg-transparent'
        }`}
        role="navigation"
        aria-label="Main navigation"
      >
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16 lg:h-20">
            {/* Logo */}
            <Link to="/" className="flex items-center gap-2 group z-50" aria-label="Sidai Resort - Home">
              <div className="relative">
                <div className="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-gradient-sunset flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-300">
                  <span className="text-sm lg:text-base font-montserrat font-bold text-primary-foreground">S</span>
                </div>
              </div>
              <div className="flex flex-col">
                <span className="text-lg lg:text-xl font-montserrat font-bold text-primary leading-tight">
                  Sidai Resort
                </span>
                <span className={`text-[10px] font-montserrat tracking-widest uppercase leading-none transition-colors duration-300 ${
                  isScrolled ? 'text-muted-foreground' : 'text-foreground/70'
                }`}>
                  Naroosura, Kenya
                </span>
              </div>
            </Link>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center gap-1 lg:gap-2">
              {navLinks.map((link) => (
                <Link
                  key={link.name}
                  to={link.path}
                  className={`relative px-4 py-2 font-montserrat text-sm font-medium rounded-lg transition-all duration-300 group ${
                    isActive(link.path)
                      ? 'text-primary'
                      : isScrolled
                        ? 'text-foreground hover:text-primary hover:bg-primary/5'
                        : 'text-foreground hover:text-primary hover:bg-card/10'
                  }`}
                  aria-current={isActive(link.path) ? 'page' : undefined}
                >
                  {link.name}
                  <span
                    className={`absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 bg-primary rounded-full transition-all duration-300 ${
                      isActive(link.path) ? 'w-6' : 'w-0 group-hover:w-6'
                    }`}
                  />
                </Link>
              ))}
              <Button
                asChild
                className="ml-3 bg-gradient-sunset font-montserrat font-semibold px-5 py-2 rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.03] transition-all duration-300 text-sm"
              >
                <Link to="/contact">
                  Book Now
                  <ChevronRight className="ml-1 w-4 h-4" />
                </Link>
              </Button>
            </div>

            {/* Mobile Menu Toggle */}
            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="md:hidden relative z-50 p-2 rounded-lg hover:bg-primary/10 transition-colors duration-300"
              aria-label={isMobileMenuOpen ? 'Close menu' : 'Open menu'}
              aria-expanded={isMobileMenuOpen}
              aria-controls="mobile-menu"
            >
              <div className="w-6 h-6 relative">
                <span className={`absolute left-0 block w-6 h-0.5 bg-primary rounded-full transition-all duration-300 ${
                  isMobileMenuOpen ? 'top-[11px] rotate-45' : 'top-1'
                }`} />
                <span className={`absolute left-0 top-[11px] block w-6 h-0.5 bg-primary rounded-full transition-all duration-300 ${
                  isMobileMenuOpen ? 'opacity-0 translate-x-3' : 'opacity-100'
                }`} />
                <span className={`absolute left-0 block w-6 h-0.5 bg-primary rounded-full transition-all duration-300 ${
                  isMobileMenuOpen ? 'top-[11px] -rotate-45' : 'top-[19px]'
                }`} />
              </div>
            </button>
          </div>
        </div>
      </nav>

      {/* Mobile Menu Overlay */}
      <div
        id="mobile-menu"
        className={`fixed inset-0 z-40 md:hidden transition-all duration-500 ${
          isMobileMenuOpen ? 'visible' : 'invisible'
        }`}
        role="dialog"
        aria-modal="true"
        aria-label="Mobile navigation menu"
      >
        {/* Backdrop */}
        <div
          className={`absolute inset-0 bg-foreground/20 backdrop-blur-sm transition-opacity duration-500 ${
            isMobileMenuOpen ? 'opacity-100' : 'opacity-0'
          }`}
          onClick={closeMobileMenu}
          aria-hidden="true"
        />

        {/* Menu Panel */}
        <div
          className={`absolute top-0 right-0 h-full w-full max-w-sm bg-card shadow-2xl transition-transform duration-500 ease-out ${
            isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'
          }`}
        >
          <div className="flex flex-col h-full pt-20 pb-8 px-6">
            {/* Nav Links */}
            <nav className="flex-1 flex flex-col gap-1" aria-label="Mobile navigation">
              {navLinks.map((link, i) => (
                <Link
                  key={link.name}
                  to={link.path}
                  onClick={closeMobileMenu}
                  className={`flex items-center justify-between px-4 py-4 rounded-xl font-montserrat text-lg font-medium transition-all duration-300 ${
                    isActive(link.path)
                      ? 'bg-primary/10 text-primary'
                      : 'text-foreground hover:bg-primary/5 hover:text-primary'
                  }`}
                  style={{ transitionDelay: isMobileMenuOpen ? `${i * 50}ms` : '0ms' }}
                  aria-current={isActive(link.path) ? 'page' : undefined}
                >
                  <span>{link.name}</span>
                  <ChevronRight className={`w-5 h-5 transition-colors ${
                    isActive(link.path) ? 'text-primary' : 'text-muted-foreground'
                  }`} />
                </Link>
              ))}
            </nav>

            {/* CTA */}
            <div className="pt-6 border-t border-border">
              <Button
                asChild
                size="lg"
                className="w-full bg-gradient-sunset font-montserrat font-bold text-base shadow-md"
              >
                <Link to="/contact" onClick={closeMobileMenu}>
                  Book Your Stay
                  <ChevronRight className="ml-2 w-5 h-5" />
                </Link>
              </Button>
              <p className="text-center text-xs text-muted-foreground mt-4 font-montserrat">
                Naroosura, Narok County &bull; Kenya
              </p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Navigation;
