import React, { useState } from 'react';
import { Camera, X } from 'lucide-react';
import heroImage from '@/assets/hero-sunset.jpg';
import conferencingImage from '@/assets/conferencing.jpg';
import swimmingImage from '@/assets/swimming-pool.jpg';
import diningImage from '@/assets/dining.jpg';
import spaImage from '@/assets/spa-wellness.jpg';

const Gallery = () => {
  const [selectedImage, setSelectedImage] = useState<{ src: string; title: string; category: string } | null>(null);
  const [activeFilter, setActiveFilter] = useState('all');

  const images = [
    { src: heroImage, title: 'Golden Sunset Over the Resort', category: 'resort', span: 'col-span-2 row-span-2' },
    { src: conferencingImage, title: 'Executive Conference Hall', category: 'conferencing', span: '' },
    { src: swimmingImage, title: 'Infinity Pool at Dusk', category: 'leisure', span: '' },
    { src: diningImage, title: 'Fine Dining Experience', category: 'dining', span: 'col-span-2' },
    { src: spaImage, title: 'Spa & Wellness Retreat', category: 'wellness', span: '' },
    { src: heroImage, title: 'Panoramic Savanna Views', category: 'resort', span: '' },
    { src: swimmingImage, title: 'Poolside Relaxation', category: 'leisure', span: 'col-span-2' },
    { src: conferencingImage, title: 'Modern Meeting Spaces', category: 'conferencing', span: '' },
    { src: diningImage, title: 'Authentic Maasai Cuisine', category: 'dining', span: '' },
    { src: spaImage, title: 'Tranquil Garden Spa', category: 'wellness', span: 'col-span-2 row-span-2' },
    { src: heroImage, title: 'Sunrise Safari Views', category: 'resort', span: '' },
    { src: swimmingImage, title: 'Family Pool Area', category: 'leisure', span: '' },
  ];

  const filters = [
    { key: 'all', label: 'All' },
    { key: 'resort', label: 'Resort' },
    { key: 'conferencing', label: 'Conferencing' },
    { key: 'dining', label: 'Dining' },
    { key: 'leisure', label: 'Leisure' },
    { key: 'wellness', label: 'Wellness' },
  ];

  const filteredImages = activeFilter === 'all' ? images : images.filter(img => img.category === activeFilter);

  return (
    <div className="min-h-screen pt-14 sm:pt-16 lg:pt-20">
      {/* Hero */}
      <section className="relative h-48 sm:h-64 lg:h-80 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <Camera className="w-10 h-10 sm:w-14 sm:h-14 text-primary mx-auto mb-3 sm:mb-4" />
          <h1 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold mb-2 sm:mb-4 animate-fade-in-up">
            Our <span className="text-primary glow-effect">Gallery</span>
          </h1>
          <p className="text-sm sm:text-lg opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            Explore the beauty and luxury of Sidai Resort through our lens
          </p>
        </div>
      </section>

      {/* Filters */}
      <section className="py-8 sm:py-12 lg:py-16 bg-background">
        <div className="container mx-auto px-4">
          {/* Filter Buttons */}
          <div className="flex flex-wrap justify-center gap-2 sm:gap-3 mb-8 sm:mb-12">
            {filters.map((filter) => (
              <button
                key={filter.key}
                onClick={() => setActiveFilter(filter.key)}
                className={`px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-montserrat font-medium text-xs sm:text-sm transition-all duration-300 transform hover:scale-105 ${
                  activeFilter === filter.key
                    ? 'bg-gradient-sunset text-white glow-effect'
                    : 'bg-muted text-muted-foreground hover:bg-primary/20 hover:text-primary'
                }`}
              >
                {filter.label}
              </button>
            ))}
          </div>

          {/* Masonry Grid */}
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 auto-rows-[180px] sm:auto-rows-[220px] lg:auto-rows-[260px]">
            {filteredImages.map((image, index) => (
              <div
                key={index}
                className={`group relative rounded-xl sm:rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 transform hover:scale-[1.02] ${
                  image.span && activeFilter === 'all' ? image.span : ''
                }`}
                onClick={() => setSelectedImage(image)}
              >
                <img
                  src={image.src}
                  alt={image.title}
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  loading="lazy"
                />
                {/* Overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                  <div className="p-3 sm:p-4 lg:p-5 w-full">
                    <h3 className="text-white font-montserrat font-semibold text-xs sm:text-sm lg:text-base mb-1">
                      {image.title}
                    </h3>
                    <span className="text-primary text-[10px] sm:text-xs font-montserrat font-medium uppercase tracking-wider">
                      {image.category}
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {filteredImages.length === 0 && (
            <div className="text-center py-16">
              <Camera className="w-12 h-12 text-muted-foreground mx-auto mb-4" />
              <p className="text-muted-foreground font-montserrat">No images found in this category.</p>
            </div>
          )}
        </div>
      </section>

      {/* Lightbox Modal */}
      {selectedImage && (
        <div
          className="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 sm:p-8"
          onClick={() => setSelectedImage(null)}
        >
          <button
            className="absolute top-4 right-4 sm:top-6 sm:right-6 p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors z-10"
            onClick={() => setSelectedImage(null)}
            aria-label="Close"
          >
            <X className="w-6 h-6 sm:w-8 sm:h-8 text-white" />
          </button>
          <div className="max-w-5xl max-h-[85vh] w-full" onClick={(e) => e.stopPropagation()}>
            <img
              src={selectedImage.src}
              alt={selectedImage.title}
              className="w-full h-auto max-h-[75vh] object-contain rounded-lg sm:rounded-2xl"
            />
            <div className="text-center mt-3 sm:mt-4">
              <h3 className="text-white font-montserrat font-semibold text-base sm:text-xl">
                {selectedImage.title}
              </h3>
              <span className="text-primary text-xs sm:text-sm font-montserrat uppercase tracking-wider">
                {selectedImage.category}
              </span>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Gallery;
