import { useLocation, Link } from "react-router-dom";
import { useEffect } from "react";
import { Button } from "@/components/ui/button";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error(
      "404 Error: User attempted to access non-existent route:",
      location.pathname
    );
  }, [location.pathname]);

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-earth pt-20">
      <div className="text-center max-w-md mx-auto px-4">
        <div className="text-8xl font-montserrat font-bold text-primary mb-4">404</div>
        <h1 className="text-3xl font-montserrat font-bold text-foreground mb-4">
          Oops! Page Not Found
        </h1>
        <p className="text-lg text-muted-foreground mb-8">
          It looks like this page got lost on the safari. Let's get you back to camp!
        </p>
        <div className="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
          <Link to="/">
            <Button 
              size="lg" 
              className="bg-gradient-sunset hover:glow-effect font-montserrat font-semibold"
            >
              Return Home
            </Button>
          </Link>
          <Link to="/services">
            <Button 
              size="lg" 
              variant="outline"
              className="border-secondary text-secondary hover:bg-secondary hover:text-secondary-foreground font-montserrat font-medium"
            >
              Explore Services
            </Button>
          </Link>
        </div>
      </div>
    </div>
  );
};

export default NotFound;
