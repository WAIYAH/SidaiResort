import { useLocation, Link } from "react-router-dom";
import { useEffect } from "react";
import { Button } from "@/components/ui/button";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error("404 Error: User attempted to access non-existent route:", location.pathname);
  }, [location.pathname]);

  return (
    <div className="min-h-[100svh] flex items-center justify-center bg-gradient-earth pt-14 sm:pt-20 px-4">
      <div className="text-center max-w-md mx-auto">
        <div className="text-6xl sm:text-8xl font-montserrat font-bold text-primary mb-4">404</div>
        <h1 className="text-2xl sm:text-3xl font-montserrat font-bold text-foreground mb-4">
          Oops! Page Not Found
        </h1>
        <p className="text-base sm:text-lg text-muted-foreground mb-8">
          It looks like this page got lost on the safari. Let's get you back to camp!
        </p>
        <div className="flex flex-col sm:flex-row gap-4 justify-center">
          <Link to="/">
            <Button size="lg" className="w-full sm:w-auto bg-gradient-sunset hover:glow-effect font-montserrat font-semibold">
              Return Home
            </Button>
          </Link>
          <Link to="/services">
            <Button size="lg" variant="outline" className="w-full sm:w-auto border-secondary text-secondary hover:bg-secondary hover:text-secondary-foreground font-montserrat font-medium">
              Explore Services
            </Button>
          </Link>
        </div>
      </div>
    </div>
  );
};

export default NotFound;
