{
  inputs = {
    devenv = {
      inputs.nixpkgs.follows = "nixpkgs";
      url = "github:cachix/devenv";
    };
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-23.05";
    phps = {
      inputs.nixpkgs.follows = "nixpkgs";
      url = "github:fossar/nix-phps";
    };
    systems.url = "github:nix-systems/default";
  };
  nixConfig = {
    extra-trusted-public-keys = "devenv.cachix.org-1:w1cLUi8dv3hnoSPGAuibQv+f9TZLr6cv/Hm9XgU50cw= cache.nixos.org-1:6NCHdD59X431o0gWypbMrAURkbJ16ZPMQFGspcDShjY= fossar.cachix.org-1:Zv6FuqIboeHPWQS7ysLCJ7UT7xExb4OE8c4LyGb5AsE= nix-community.cachix.org-1:mB9FSh9qf2dCimDSUo8Zy7bkq5CX+/rkCWyvRCYg3Fs=";
    extra-substituters = "https://devenv.cachix.org https://cache.nixos.org https://fossar.cachix.org https://nix-community.cachix.org";
  };
  outputs = {
    devenv,
    nixpkgs,
    phps,
    self,
    systems,
    ...
  } @ inputs: let
    forEachSystem = nixpkgs.lib.genAttrs (import systems);
    php-version = "8.2";
  in {
    devShells = forEachSystem (system: let
      pkgs = nixpkgs.legacyPackages.${system};
    in {
      default = devenv.lib.mkShell {
        inherit inputs pkgs;
        modules = [
          ({config, ...}: {
            languages = {
              php = {
                enable = true;
                extensions = ["xdebug"];
                ini = ''
                  assert.exception = 1
                  error_reporting = -1
                  log_errors_max_len = 0
                  memory_limit = -1
                  xdebug.mode = coverage,debug
                  xdebug.show_exception_trace = 0
                  xdebug.start_with_request = yes
                  zend.assertions = 1
                '';
                package = phps.packages.${system}.php;
                version = php-version;
              };
            };
            packages = [pkgs.toybox];
          })
        ];
      };
    });
  };
}