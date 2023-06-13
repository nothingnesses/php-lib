# Run development environment with:
# nix develop --impure
# Then run server with:
# devenv up
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
                upload_max_filesize = 128M
                memory_limit = 1G
                xdebug.mode = debug
                xdebug.start_with_request = yes
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
