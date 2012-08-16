guard 'phpunit', :all_on_start => false, :tests_path => 'src/Test', :cli => '--colors --bootstrap src/bootstrap.php' do
  # watch test files
  watch(%r{^.+Test\.php$})

  #watch Domain
  watch(%r{^src/Domain/(.+)\.php}) { |m| "src/Test/Unit/Domain/#{m[1]}Test\.php" }

  #watch Presentation
  watch(%r{^src/Presentation/(.+)\.php}) { |m| "src/Test/Unit/Presentation/#{m[1]}Test\.php" }

end

