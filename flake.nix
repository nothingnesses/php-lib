{
  inputs = {
    devenv = {
      inputs.nixpkgs.follows = "nixpkgs";
      url = "github:cachix/devenv";
    };
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    phps = {
      inputs.nixpkgs.follows = "nixpkgs";
      url = "github:fossar/nix-phps";
    };
  };
  outputs = {
    self,
    nixpkgs,
    devenv,
    phps,
    ...
  } @ inputs: let
    system = "x86_64-linux";
    php-version = "8.2";
    pkgs = nixpkgs.legacyPackages.${system};
    in {
    devShell.${system} = devenv.lib.mkShell {
      inherit inputs pkgs;
      modules = [
        ({
          pkgs,
          config,
          ...
        }: {
          languages = {
            php = {
              enable = true;
              extensions = ["xdebug"];
              ini = ''
                assert.exception=1
                error_reporting=-1
                log_errors_max_len=0
                memory_limit=-1
                upload_max_filesize=128M
                xdebug.mode=coverage,debug
                xdebug.show_exception_trace=0
                xdebug.start_with_request=yes
                zend.assertions=1
              '';
              package = phps.packages.${system}.php;
              version = php-version;
            };
          };
          packages = [pkgs.toybox];
        })
      ];
    };
  };
}
