import React from 'react';
import { Link } from 'react-router-dom';
import { Shield, Eye, Database, Share2, Lock, UserCheck, Globe, Bell, FileText } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';

const PrivacyPolicy = () => {
  const sections = [
    {
      icon: Eye,
      title: '1. Information We Collect',
      content: [
        {
          subtitle: 'Personal Information',
          text: 'When you make a reservation, contact us, or use our services, we may collect your name, email address, phone number, postal address, payment information, passport/ID details, and travel preferences.'
        },
        {
          subtitle: 'Automatically Collected Information',
          text: 'We automatically collect certain information when you visit our website, including your IP address, browser type, device information, pages visited, time spent on pages, and referring URLs.'
        },
        {
          subtitle: 'Cookies & Tracking',
          text: 'We use cookies and similar tracking technologies to enhance your browsing experience. See our Cookie Policy for more details.'
        }
      ]
    },
    {
      icon: Database,
      title: '2. How We Use Your Information',
      content: [
        {
          subtitle: 'Service Delivery',
          text: 'To process reservations, manage your stay, provide requested services, and communicate important updates about your booking.'
        },
        {
          subtitle: 'Communication',
          text: 'To send you booking confirmations, pre-arrival information, post-stay surveys, promotional offers (with your consent), and respond to your inquiries.'
        },
        {
          subtitle: 'Improvement',
          text: 'To analyze usage patterns, improve our website and services, personalize your experience, and conduct internal research and analytics.'
        }
      ]
    },
    {
      icon: Share2,
      title: '3. Information Sharing & Disclosure',
      content: [
        {
          subtitle: 'Service Providers',
          text: 'We may share your information with trusted third-party service providers who assist us in operating our resort, processing payments, arranging transfers, and delivering services. These providers are contractually obligated to protect your data.'
        },
        {
          subtitle: 'Legal Requirements',
          text: 'We may disclose your information when required by law, court order, or government regulation, or when we believe disclosure is necessary to protect our rights, your safety, or the safety of others.'
        },
        {
          subtitle: 'No Sale of Data',
          text: 'We do not sell, rent, or trade your personal information to third parties for their marketing purposes.'
        }
      ]
    },
    {
      icon: Lock,
      title: '4. Data Security',
      content: [
        {
          subtitle: 'Protection Measures',
          text: 'We implement industry-standard security measures including SSL encryption, secure payment processing, access controls, and regular security audits to protect your personal information from unauthorized access, alteration, or destruction.'
        },
        {
          subtitle: 'Data Retention',
          text: 'We retain your personal information only for as long as necessary to fulfill the purposes outlined in this policy, comply with legal obligations, resolve disputes, and enforce our agreements. Booking records are retained for 7 years for tax and legal compliance.'
        }
      ]
    },
    {
      icon: UserCheck,
      title: '5. Your Rights & Choices',
      content: [
        {
          subtitle: 'Access & Correction',
          text: 'You have the right to access, correct, or update your personal information at any time by contacting us at privacy@sidairesort.com.'
        },
        {
          subtitle: 'Deletion',
          text: 'You may request deletion of your personal data, subject to certain legal exceptions. We will respond to your request within 30 days.'
        },
        {
          subtitle: 'Marketing Opt-Out',
          text: 'You can opt out of marketing communications at any time by clicking the "unsubscribe" link in our emails or contacting us directly.'
        }
      ]
    },
    {
      icon: Globe,
      title: '6. International Data Transfers',
      content: [
        {
          subtitle: 'Cross-Border Transfers',
          text: 'As an international resort, your data may be transferred to and processed in countries outside Kenya. We ensure appropriate safeguards are in place, including standard contractual clauses and compliance with the Kenya Data Protection Act, 2019.'
        }
      ]
    },
    {
      icon: Bell,
      title: '7. Changes to This Policy',
      content: [
        {
          subtitle: 'Updates',
          text: 'We may update this Privacy Policy periodically to reflect changes in our practices, technology, legal requirements, or other factors. We will notify you of any material changes by posting the updated policy on our website with a revised "Last Updated" date.'
        }
      ]
    }
  ];

  return (
    <div className="min-h-screen pt-14 sm:pt-16 lg:pt-20">
      {/* Hero */}
      <section className="relative h-48 sm:h-64 lg:h-80 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <Shield className="w-10 h-10 sm:w-14 sm:h-14 text-primary mx-auto mb-3 sm:mb-4" />
          <h1 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold mb-2 sm:mb-4 animate-fade-in-up">
            Privacy Policy
          </h1>
          <p className="text-sm sm:text-lg opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            Your privacy matters to us. Here's how we protect your information.
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
                  Sidai Resort ("we," "our," or "us"), located in Naroosura, Narok County, Kenya, is committed to protecting the privacy 
                  and security of your personal information. This Privacy Policy explains how we collect, use, disclose, 
                  and safeguard your information when you visit our website, make a reservation, or use our services.
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
                    <div className="space-y-4 sm:space-y-5">
                      {section.content.map((item, i) => (
                        <div key={i}>
                          <h3 className="font-montserrat font-semibold text-secondary text-sm sm:text-base mb-1 sm:mb-2">
                            {item.subtitle}
                          </h3>
                          <p className="text-muted-foreground text-sm sm:text-base leading-relaxed">
                            {item.text}
                          </p>
                        </div>
                      ))}
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>

            <Card className="neumorphism mt-8">
              <CardContent className="p-4 sm:p-6 text-center">
                <FileText className="w-8 h-8 text-primary mx-auto mb-3" />
                <h3 className="font-montserrat font-semibold text-foreground mb-2">Contact Our Privacy Team</h3>
                <p className="text-muted-foreground text-sm sm:text-base mb-1">
                  For questions or concerns about this policy, contact us at:
                </p>
                <p className="text-primary font-montserrat font-medium">privacy@sidairesort.com</p>
                <p className="text-muted-foreground text-sm mt-1">Sidai Resort, P.O. Box 12345, Naroosura, Narok County, Kenya</p>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
};

export default PrivacyPolicy;
