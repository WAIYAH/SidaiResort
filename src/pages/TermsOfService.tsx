import React from 'react';
import { Scale, BookOpen, CreditCard, Ban, AlertTriangle, ShieldCheck, Gavel, FileText } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';

const TermsOfService = () => {
  const sections = [
    {
      icon: BookOpen,
      title: '1. Acceptance of Terms',
      items: [
        'By accessing or using the Sidai Resort website and services, you agree to be bound by these Terms of Service.',
        'If you do not agree with any part of these terms, you may not use our services.',
        'We reserve the right to modify these terms at any time. Continued use of our services constitutes acceptance of updated terms.',
        'These terms apply to all visitors, guests, clients, and any other users of our services.'
      ]
    },
    {
      icon: CreditCard,
      title: '2. Reservations & Payments',
      items: [
        'All reservations are subject to availability and confirmation by Sidai Resort.',
        'A deposit of 30% of the total booking value is required to confirm your reservation.',
        'Full payment is due 14 days prior to your arrival date. Late payments may result in cancellation.',
        'We accept major credit/debit cards, bank transfers, and mobile money (M-Pesa). All prices are quoted in USD unless otherwise stated.',
        'Rates are subject to change without notice. Confirmed reservations are honored at the quoted rate.',
        'Additional charges for extra services, minibar, and incidentals will be settled upon checkout.'
      ]
    },
    {
      icon: Ban,
      title: '3. Cancellation & Refund Policy',
      items: [
        'Cancellations made 30+ days before arrival: Full refund minus 10% administrative fee.',
        'Cancellations made 15-29 days before arrival: 50% refund of the total booking value.',
        'Cancellations made 7-14 days before arrival: 25% refund of the total booking value.',
        'Cancellations made less than 7 days before arrival or no-shows: No refund.',
        'Date changes are subject to availability and may incur additional charges if rates have changed.',
        'Force majeure events (natural disasters, pandemics, government restrictions) will be handled on a case-by-case basis with maximum flexibility.'
      ]
    },
    {
      icon: ShieldCheck,
      title: '4. Guest Responsibilities',
      items: [
        'Guests must provide valid identification (passport or national ID) upon check-in.',
        'Guests are expected to treat resort property, staff, wildlife, and other guests with respect.',
        'Guests are liable for any damage caused to resort property during their stay.',
        'Smoking is only permitted in designated areas. A cleaning fee of $250 applies for smoking in non-designated areas.',
        'Pets are not allowed on the resort premises unless prior written approval has been obtained.',
        'Guests must follow all safety guidelines during safari activities, pool usage, and other resort experiences.',
        'The resort is not responsible for loss or theft of personal belongings. Safe deposit boxes are available at reception.'
      ]
    },
    {
      icon: AlertTriangle,
      title: '5. Limitation of Liability',
      items: [
        'Sidai Resort shall not be liable for any indirect, incidental, special, or consequential damages arising from the use of our services.',
        'Our total liability for any claim shall not exceed the amount you paid for the specific service giving rise to the claim.',
        'We are not responsible for delays or failures caused by circumstances beyond our reasonable control, including but not limited to natural events, strikes, or government actions.',
        'Safari and outdoor activities carry inherent risks. Guests participate at their own risk and may be required to sign a liability waiver.',
        'The resort is not liable for any health issues arising from pre-existing medical conditions not disclosed prior to activities.'
      ]
    },
    {
      icon: Scale,
      title: '6. Intellectual Property',
      items: [
        'All content on the Sidai Resort website, including text, images, logos, and design elements, is the property of Sidai Resort and protected by Kenyan and international copyright laws.',
        'Guests may share photos of their stay on personal social media with credit to Sidai Resort.',
        'Commercial use of any resort imagery or content requires prior written consent.',
        'The Sidai Resort name, logo, and branding are registered trademarks and may not be used without authorization.'
      ]
    },
    {
      icon: Gavel,
      title: '7. Governing Law & Disputes',
      items: [
        'These Terms of Service are governed by the laws of the Republic of Kenya.',
        'Any disputes shall first be resolved through amicable negotiation between the parties.',
        'If negotiation fails, disputes shall be submitted to mediation under the Nairobi Centre for International Arbitration rules.',
        'If mediation is unsuccessful, disputes shall be resolved by arbitration in Nairobi, Kenya.',
        'Nothing in these terms limits your statutory rights as a consumer under Kenyan law.'
      ]
    }
  ];

  return (
    <div className="min-h-screen pt-14 sm:pt-16 lg:pt-20">
      <section className="relative h-48 sm:h-64 lg:h-80 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <Scale className="w-10 h-10 sm:w-14 sm:h-14 text-primary mx-auto mb-3 sm:mb-4" />
          <h1 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold mb-2 sm:mb-4 animate-fade-in-up">
            Terms of Service
          </h1>
          <p className="text-sm sm:text-lg opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            Please read these terms carefully before using our services.
          </p>
        </div>
      </section>

      <section className="py-12 sm:py-16 lg:py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <Card className="neumorphism mb-8">
              <CardContent className="p-4 sm:p-6">
                <p className="text-muted-foreground text-sm sm:text-base">
                  <strong className="text-foreground">Last Updated:</strong> March 1, 2026 &nbsp;|&nbsp;
                  <strong className="text-foreground">Effective Date:</strong> March 1, 2026
                </p>
                <p className="text-muted-foreground mt-3 text-sm sm:text-base leading-relaxed">
                  Welcome to Sidai Resort. These Terms of Service govern your use of our website, 
                  reservation services, and all amenities and experiences offered at our resort located 
                  in Naroosura, Narok County, Kenya.
                </p>
              </CardContent>
            </Card>

            <div className="space-y-6 sm:space-y-8">
              {sections.map((section, index) => (
                <Card key={index} className="glassmorphism overflow-hidden">
                  <CardContent className="p-4 sm:p-8">
                    <div className="flex items-center gap-3 mb-4 sm:mb-6">
                      <section.icon className="w-6 h-6 sm:w-7 sm:h-7 text-primary flex-shrink-0" />
                      <h2 className="text-xl sm:text-2xl font-montserrat font-bold text-foreground">
                        {section.title}
                      </h2>
                    </div>
                    <ul className="space-y-3">
                      {section.items.map((item, i) => (
                        <li key={i} className="flex items-start gap-2 sm:gap-3">
                          <span className="w-1.5 h-1.5 rounded-full bg-secondary flex-shrink-0 mt-2" />
                          <p className="text-muted-foreground text-sm sm:text-base leading-relaxed">{item}</p>
                        </li>
                      ))}
                    </ul>
                  </CardContent>
                </Card>
              ))}
            </div>

            <Card className="neumorphism mt-8">
              <CardContent className="p-4 sm:p-6 text-center">
                <FileText className="w-8 h-8 text-primary mx-auto mb-3" />
                <h3 className="font-montserrat font-semibold text-foreground mb-2">Questions About Our Terms?</h3>
                <p className="text-muted-foreground text-sm sm:text-base mb-1">
                  Contact our legal team for clarification:
                </p>
                <p className="text-primary font-montserrat font-medium">legal@sidairesort.com</p>
                <p className="text-muted-foreground text-sm mt-1">Sidai Resort, P.O. Box 12345, Naroosura, Narok County, Kenya</p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
};

export default TermsOfService;
