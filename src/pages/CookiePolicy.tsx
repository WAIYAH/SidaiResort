import React from 'react';
import { Cookie, Settings, BarChart3, Shield, ToggleLeft, Info, FileText } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';

const CookiePolicy = () => {
  const cookieTypes = [
    {
      name: 'Essential Cookies',
      required: true,
      description: 'These cookies are strictly necessary for the website to function and cannot be disabled. They are set in response to actions you take such as setting privacy preferences, logging in, or filling in forms.',
      examples: ['Session management', 'Security tokens', 'Language preferences', 'Cookie consent status']
    },
    {
      name: 'Analytics Cookies',
      required: false,
      description: 'These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. This helps us improve our website performance and user experience.',
      examples: ['Page visit counts', 'Traffic sources', 'Time spent on pages', 'Navigation patterns']
    },
    {
      name: 'Functional Cookies',
      required: false,
      description: 'These cookies enable enhanced functionality and personalization, such as remembering your preferences, greeting you by name, or showing you content relevant to your region.',
      examples: ['Saved booking preferences', 'Currency settings', 'Recently viewed rooms', 'Personalized recommendations']
    },
    {
      name: 'Marketing Cookies',
      required: false,
      description: 'These cookies are used to deliver advertisements more relevant to you and your interests. They may be set by us or by advertising partners and are used to build a profile of your interests and show you relevant ads on other sites.',
      examples: ['Targeted advertising', 'Social media sharing', 'Campaign effectiveness measurement', 'Retargeting ads']
    }
  ];

  const sections = [
    {
      icon: Info,
      title: '1. What Are Cookies?',
      text: 'Cookies are small text files that are placed on your device (computer, tablet, or smartphone) when you visit a website. They are widely used to make websites work more efficiently, provide a better browsing experience, and give website owners useful information about how their site is being used. Cookies can be "persistent" (stored until they expire or are deleted) or "session" cookies (deleted when you close your browser).'
    },
    {
      icon: Settings,
      title: '3. Managing Your Cookie Preferences',
      text: 'You can control and manage cookies in several ways. Most web browsers allow you to manage cookies through their settings. You can set your browser to refuse cookies, delete existing cookies, or alert you when a cookie is being placed. Please note that disabling essential cookies may affect the functionality of our website. You can also manage preferences for specific cookie categories through our cookie consent banner shown on your first visit.'
    },
    {
      icon: BarChart3,
      title: '4. Third-Party Cookies',
      text: 'Some cookies on our website are placed by third-party services that appear on our pages. We do not control the dissemination of these cookies. These third parties include analytics providers (such as Google Analytics), social media platforms, and advertising networks. We recommend reviewing the privacy policies of these third parties for more information about their cookie practices.'
    },
    {
      icon: Shield,
      title: '5. Data Protection',
      text: 'Information collected through cookies is processed in accordance with our Privacy Policy and the Kenya Data Protection Act, 2019. We ensure that any personal data collected via cookies is handled securely and used only for the purposes described in this policy. We do not use cookies to collect sensitive personal information without your explicit consent.'
    },
    {
      icon: ToggleLeft,
      title: '6. Updates to This Policy',
      text: 'We may update this Cookie Policy from time to time to reflect changes in technology, legislation, or our data practices. Any changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically to stay informed about how we use cookies.'
    }
  ];

  return (
    <div className="min-h-screen pt-14 sm:pt-16 lg:pt-20">
      <section className="relative h-48 sm:h-64 lg:h-80 flex items-center justify-center bg-gradient-savanna overflow-hidden">
        <div className="absolute inset-0 maasai-pattern opacity-20"></div>
        <div className="text-center text-white z-10 max-w-4xl px-4">
          <Cookie className="w-10 h-10 sm:w-14 sm:h-14 text-primary mx-auto mb-3 sm:mb-4" />
          <h1 className="text-3xl sm:text-4xl md:text-5xl font-montserrat font-bold mb-2 sm:mb-4 animate-fade-in-up">
            Cookie Policy
          </h1>
          <p className="text-sm sm:text-lg opacity-90 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            How we use cookies to improve your experience at Sidai Resort.
          </p>
        </div>
      </section>

      <section className="py-12 sm:py-16 lg:py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <Card className="neumorphism mb-8">
              <CardContent className="p-4 sm:p-6">
                <p className="text-muted-foreground text-sm sm:text-base">
                  <strong className="text-foreground">Last Updated:</strong> March 1, 2026
                </p>
                <p className="text-muted-foreground mt-3 text-sm sm:text-base leading-relaxed">
                  This Cookie Policy explains how Sidai Resort uses cookies and similar technologies 
                  when you visit our website at sidairesort.com. By continuing to browse our site, 
                  you consent to our use of cookies as described in this policy.
                </p>
              </CardContent>
            </Card>

            {/* What are cookies */}
            <Card className="glassmorphism overflow-hidden mb-6 sm:mb-8">
              <CardContent className="p-4 sm:p-8">
                <div className="flex items-center gap-3 mb-4">
                  <Info className="w-6 h-6 sm:w-7 sm:h-7 text-primary flex-shrink-0" />
                  <h2 className="text-xl sm:text-2xl font-montserrat font-bold text-foreground">
                    1. What Are Cookies?
                  </h2>
                </div>
                <p className="text-muted-foreground text-sm sm:text-base leading-relaxed">
                  {sections[0].text}
                </p>
              </CardContent>
            </Card>

            {/* Cookie Types */}
            <Card className="glassmorphism overflow-hidden mb-6 sm:mb-8">
              <CardContent className="p-4 sm:p-8">
                <div className="flex items-center gap-3 mb-4 sm:mb-6">
                  <Cookie className="w-6 h-6 sm:w-7 sm:h-7 text-primary flex-shrink-0" />
                  <h2 className="text-xl sm:text-2xl font-montserrat font-bold text-foreground">
                    2. Types of Cookies We Use
                  </h2>
                </div>
                <div className="space-y-4 sm:space-y-6">
                  {cookieTypes.map((cookie, index) => (
                    <div key={index} className="border border-border rounded-lg p-3 sm:p-5">
                      <div className="flex items-center justify-between mb-2 sm:mb-3 flex-wrap gap-2">
                        <h3 className="font-montserrat font-semibold text-foreground text-sm sm:text-lg">
                          {cookie.name}
                        </h3>
                        <span className={`text-xs font-montserrat font-medium px-2 py-1 rounded-full ${
                          cookie.required 
                            ? 'bg-secondary/20 text-secondary' 
                            : 'bg-muted text-muted-foreground'
                        }`}>
                          {cookie.required ? 'Required' : 'Optional'}
                        </span>
                      </div>
                      <p className="text-muted-foreground text-sm sm:text-base leading-relaxed mb-2 sm:mb-3">
                        {cookie.description}
                      </p>
                      <div className="flex flex-wrap gap-1.5 sm:gap-2">
                        {cookie.examples.map((example, i) => (
                          <span key={i} className="text-xs bg-muted px-2 py-1 rounded-md text-muted-foreground">
                            {example}
                          </span>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              </CardContent>
            </Card>

            {/* Remaining sections */}
            {sections.slice(1).map((section, index) => (
              <Card key={index} className="glassmorphism overflow-hidden mb-6 sm:mb-8">
                <CardContent className="p-4 sm:p-8">
                  <div className="flex items-center gap-3 mb-4">
                    <section.icon className="w-6 h-6 sm:w-7 sm:h-7 text-primary flex-shrink-0" />
                    <h2 className="text-xl sm:text-2xl font-montserrat font-bold text-foreground">
                      {section.title}
                    </h2>
                  </div>
                  <p className="text-muted-foreground text-sm sm:text-base leading-relaxed">
                    {section.text}
                  </p>
                </CardContent>
              </Card>
            ))}

            <Card className="neumorphism mt-8">
              <CardContent className="p-4 sm:p-6 text-center">
                <FileText className="w-8 h-8 text-primary mx-auto mb-3" />
                <h3 className="font-montserrat font-semibold text-foreground mb-2">Cookie Questions?</h3>
                <p className="text-muted-foreground text-sm sm:text-base mb-1">
                  For questions about our use of cookies, contact:
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

export default CookiePolicy;
