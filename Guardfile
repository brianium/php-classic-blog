guard 'phpunit', :all_on_start => false, :tests_path => 'Test', :cli => '--colors --bootstrap Test/bootstrap.php' do
  # watch test files
  watch(%r{^.+Test\.php$})

  #watch Domain
  watch(%r{^Domain/(.+)\.php}) { |m| "Test/Unit/Domain/#{m[1]}Test.php" }
end